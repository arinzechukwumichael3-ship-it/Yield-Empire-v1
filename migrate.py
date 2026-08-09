#!/usr/bin/env python3
"""Convert MySQL/MariaDB phpMyAdmin dump (database/ibanking.sql) into
a PostgreSQL-compatible migration for Supabase.

Output: supabase/migrations/0001_ibanking_mysql_to_postgres.sql
"""
import re

SRC = "database/ibanking.sql"
OUT = "supabase/migrations/0001_ibanking_mysql_to_postgres.sql"

TYPE_MAP = [
    (re.compile(r"^bigint(?:\(\d+\))? unsigned$"), "bigint"),
    (re.compile(r"^bigint(?:\(\d+\))?$"), "bigint"),
    (re.compile(r"^int(?:\(\d+\))? unsigned$"), "bigint"),
    (re.compile(r"^int(?:\(\d+\))?$"), "integer"),
    (re.compile(r"^mediumint(?:\(\d+\))?$"), "integer"),
    (re.compile(r"^smallint(?:\(\d+\))? unsigned$"), "integer"),
    (re.compile(r"^smallint(?:\(\d+\))?$"), "smallint"),
    (re.compile(r"^tinyint(?:\(\d+\))?$"), "smallint"),
    (re.compile(r"^decimal\(\d+,\d+\)$"), lambda m: "numeric" + m[m.find("("):]),
    (re.compile(r"^decimal$"), "numeric"),
    (re.compile(r"^float(?:\(\d+,\d+\))?$"), "real"),
    (re.compile(r"^double(?: precision)?$"), "double precision"),
    (re.compile(r"^varchar\(\d+\)$"), lambda m: m),
    (re.compile(r"^char\(\d+\)$"), lambda m: m),
    (re.compile(r"^text$"), "text"),
    (re.compile(r"^tinytext$"), "text"),
    (re.compile(r"^mediumtext$"), "text"),
    (re.compile(r"^longtext$"), "text"),
    (re.compile(r"^blob$"), "bytea"),
    (re.compile(r"^timestamp$"), "timestamp"),
    (re.compile(r"^datetime$"), "timestamp"),
    (re.compile(r"^date$"), "date"),
    (re.compile(r"^time$"), "time"),
    (re.compile(r"^enum\(.*\)$"), "text"),
    (re.compile(r"^set\(.*\)$"), "text"),
    (re.compile(r"^json$"), "jsonb"),
]


def map_type(t):
    t = t.strip().lower()
    for rx, repl in TYPE_MAP:
        if rx.match(t):
            return repl(t) if callable(repl) else repl
    # fallback: strip size
    base = re.sub(r"\(.*\)", "", t)
    return base if base else "text"


def remove_comments(sql):
    """Strip SQL line (--) and block (/* */) comments, keeping content
    inside string literals intact."""
    out = []
    i = 0
    n = len(sql)
    instr = False
    esc = False
    inblock = False
    while i < n:
        c = sql[i]
        nxt = sql[i + 1] if i + 1 < n else ""
        if inblock:
            if c == "*" and nxt == "/":
                inblock = False
                i += 2
            else:
                i += 1
            continue
        if instr:
            out.append(c)
            if esc:
                esc = False
            elif c == "\\":
                esc = True
            elif c == "'":
                instr = False
            i += 1
            continue
        if c == "'":
            instr = True
            out.append(c)
            i += 1
            continue
        if c == "-" and nxt == "-":
            # line comment
            j = i + 2
            while j < n and sql[j] != "\n":
                j += 1
            i = j
            continue
        if c == "/" and nxt == "*":
            inblock = True
            i += 2
            continue
        out.append(c)
        i += 1
    return "".join(out)


def strip_backticks(s):
    return s.replace("`", "")


def quote_cols(cols):
    return ", ".join('"%s"' % c.strip() for c in cols.split(",") if c.strip())


def split_top(s):
    """Split a body on top-level commas."""
    parts, cur, depth, instr, esc = [], [], 0, False, False
    for ch in s:
        if instr:
            cur.append(ch)
            if esc:
                esc = False
            elif ch == "\\":
                esc = True
            elif ch == "'":
                instr = False
            continue
        if ch == "'":
            instr = True
            cur.append(ch)
            continue
        if ch == "(":
            depth += 1
            cur.append(ch)
            continue
        if ch == ")":
            depth -= 1
            cur.append(ch)
            continue
        if ch == "," and depth == 0:
            parts.append("".join(cur).strip())
            cur = []
            continue
        cur.append(ch)
    if cur:
        parts.append("".join(cur).strip())
    return parts


def split_statements(sql):
    stmts = []
    cur = []
    instr = False
    esc = False
    i = 0
    n = len(sql)
    while i < n:
        c = sql[i]
        if instr:
            cur.append(c)
            if esc:
                esc = False
            elif c == "\\":
                esc = True
            elif c == "'":
                instr = False
            i += 1
            continue
        if c == "'":
            instr = True
            cur.append(c)
            i += 1
            continue
        if c == ";":
            stmts.append("".join(cur).strip())
            cur = []
            i += 1
            continue
        cur.append(c)
        i += 1
    if cur:
        stmts.append("".join(cur).strip())
    return [s for s in stmts if s]


def clean_default(tok):
    t = tok.strip()
    if t.upper() == "NULL":
        return "NULL"
    if t.upper() in ("CURRENT_TIMESTAMP", "CURRENT_TIMESTAMP()"):
        return "CURRENT_TIMESTAMP"
    return t


def convert_column_def(coldef):
    """Convert one column definition to PostgreSQL."""
    cd = strip_backticks(coldef).strip()
    # remove trailing comment
    m = re.search(r"\sCOMMENT\s+'", cd)
    if m:
        cd = cd[:m.start()]
    m = re.match(r"([a-zA-Z_][a-zA-Z0-9_]*)\s+(.*)$", cd)
    if not m:
        return cd
    col = m.group(1)
    rest = m.group(2).strip()
    # split type + attrs
    tokens = rest.split()
    # type token may span multiple whitespace-separated tokens
    # when it contains parens (e.g. enum('a','b c'))
    typ = tokens[0] if tokens else "text"
    bal = 0
    idx = 0
    for tok in tokens:
        if idx == 0:
            bal = tok.count("(") - tok.count(")")
            idx += 1
            if bal > 0:
                continue
            break
        typ += " " + tok
        bal += tok.count("(") - tok.count(")")
        idx += 1
        if bal <= 0:
            break
    type_tokens = idx
    attrs = tokens[type_tokens:]
    # handle enum with spaces? no spaces in our dump
    ptype = map_type(typ)
    out = ['"%s"' % col, ptype]
    i = 0
    while i < len(attrs):
        a = attrs[i].upper()
        if a == "UNSIGNED":
            i += 1
            continue
        if a == "NOT" and i + 1 < len(attrs) and attrs[i + 1].upper() == "NULL":
            out.append("NOT NULL")
            i += 2
            continue
        if a == "NULL":
            out.append("NULL")
            i += 1
            continue
        if a == "DEFAULT":
            val = attrs[i + 1] if i + 1 < len(attrs) else ""
            out.append("DEFAULT " + clean_default(val))
            i += 2
            continue
        if a == "AUTO_INCREMENT":
            i += 1
            continue
        if a == "COMMENT":
            # comment contains spaces, but we stripped earlier
            i += 1
            continue
        if a in ("CHARACTER", "COLLATE"):
            # skip CHARACTER SET <x> / COLLATE <x>
            if a == "CHARACTER":
                i += 3
            else:
                i += 2
            continue
        if a == "CHECK":
            # MySQL CHECK (expr) - consume balanced parens, drop
            i += 1
            depth = 0
            while i < len(attrs):
                tok = attrs[i]
                depth += tok.count("(") - tok.count(")")
                i += 1
                if depth <= 0:
                    break
            continue
        # unknown attribute, keep safe
        out.append(attrs[i])
        i += 1
    return "  ".join(out)


def parse_create_table(stmt):
    """Parse CREATE TABLE statement -> (table_name, [column defs])."""
    m = re.match(r"CREATE TABLE\s+`?([a-zA-Z_0-9]+)`?\s*\(", stmt)
    if not m:
        return None
    tname = m.group(1)
    body = stmt[m.end():]
    # remove trailing ENGINE=... etc and closing paren
    body = re.sub(r"\)\s*ENGINE=.*$", ")", body, flags=re.S | re.I)
    body = body.rstrip()
    if body.endswith(")"):
        body = body[:-1]
    body = re.sub(r"\s*\)\s*DEFAULT CHARSET.*$", "", body, flags=re.S | re.I)
    defs = split_top(body)
    cols = []
    for d in defs:
        raw = d.strip()
        if raw.startswith("`"):
            # quoted identifier => column definition (e.g. `key` varchar(100) ...)
            cols.append(convert_column_def(d))
            continue
        # unquoted table-level constraints (PRIMARY KEY, UNIQUE KEY, KEY ..., FOREIGN KEY, ...)
        if re.match(
            r"^(PRIMARY KEY|UNIQUE( KEY| INDEX)?\b|KEY\b|INDEX\b|FOREIGN KEY|CONSTRAINT|FULLTEXT\b)",
            raw, re.I,
        ):
            continue  # handled by ALTER blocks
        cols.append(convert_column_def(d))
    return tname, cols


def mysql_decode(s):
    """Decode a MySQL-escaped string body (content between quotes) into
    the real characters. MySQL keeps \\uXXXX literals as-is."""
    out = []
    i = 0
    n = len(s)
    while i < n:
        c = s[i]
        if c == "\\" and i + 1 < n:
            nxt = s[i + 1]
            m = {
                "0": "\0", "b": "\b", "n": "\n", "r": "\r",
                "t": "\t", "Z": "\x1a", "'": "'", '"': '"',
                "\\": "\\", "%": "%", "_": "_",
            }
            if nxt == "u":
                # keep unicode escape verbatim (JSON semantics)
                j = i + 2
                while j < n and j < i + 6 and s[j] in "0123456789abcdefABCDEF":
                    j += 1
                out.append(s[i:j])
                i = j
                continue
            if nxt in m:
                out.append(m[nxt])
                i += 2
                continue
            # unknown escape: backslash dropped, char kept (MySQL behaviour)
            out.append(nxt)
            i += 2
            continue
        out.append(c)
        i += 1
    return "".join(out)


def pg_encode(s):
    """Encode a string body for standard PostgreSQL SQL (quotes doubled,
    backslashes literal)."""
    return s.replace("'", "''")


def normalize_insert_strings(insert_body):
    """Given a chunk that starts right after 'VALUES' (or the whole INSERT
    text), convert MySQL-escaped string literals into standard-conforming
    postgres literals. Non-string tokens pass through untouched."""
    out = []
    i = 0
    n = len(insert_body)
    in_str = False
    while i < n:
        c = insert_body[i]
        if in_str:
            # we are inside a string; the opening quote was already added.
            # collect raw escaped body until closing quote
            j = i
            buf = []
            while j < n:
                c2 = insert_body[j]
                if c2 == "\\":
                    buf.append("\\")
                    if j + 1 < n:
                        buf.append(insert_body[j + 1])
                        j += 2
                        continue
                elif c2 == "'":
                    break
                else:
                    buf.append(c2)
                j += 1
            raw = "".join(buf)
            out.append(pg_encode(mysql_decode(raw)))
            out.append("'")
            i = j + 1  # skip closing quote
            in_str = False
            continue
        if c == "'":
            in_str = True
            out.append("'")
            i += 1
            continue
        out.append(c)
        i += 1
    return "".join(out)


def convert_insert(stmt):
    s = strip_backticks(stmt)
    m = re.match(r"INSERT INTO\s+([a-zA-Z_0-9]+)\s+\((.*?)\)\s*VALUES\s*(.*)$", s, re.S)
    if m:
        tname, cols, vals = m.group(1), m.group(2), m.group(3)
        qcols = ", ".join('"%s"' % c.strip() for c in cols.split(","))
        vals = normalize_insert_strings(vals)
        return f"INSERT INTO {tname} ({qcols}) VALUES {vals};"
    return s + ";"


def convert_alter_index(stmt):
    """Convert ALTER TABLE ... ADD PRIMARY KEY / UNIQUE KEY / KEY block
    into postgres statements. Returns list of sql statements."""
    m = re.match(r"ALTER TABLE\s+`?([a-zA-Z_0-9]+)`?\s*(.*)$", stmt, re.S)
    if not m:
        return []
    tname = m.group(1)
    body = m.group(2).rstrip(";").strip()
    outs = []
    for part in split_top(body):
        part = strip_backticks(part).strip()
        pm = re.match(r"ADD (PRIMARY KEY)\s*\((.*)\)", part, re.S)
        if pm:
            outs.append(f"ALTER TABLE {tname} ADD PRIMARY KEY ({quote_cols(pm.group(2))});")
            continue
        um = re.match(r"ADD UNIQUE KEY\s+([a-zA-Z_0-9]+)\s*\((.*)\)", part, re.S)
        if um:
            outs.append(f"ALTER TABLE {tname} ADD CONSTRAINT {um.group(1)} UNIQUE ({quote_cols(um.group(2))});")
            continue
        km = re.match(r"ADD KEY\s+([a-zA-Z_0-9]+)\s*\((.*)\)", part, re.S)
        if km:
            outs.append(f"CREATE INDEX {km.group(1)} ON {tname} ({quote_cols(km.group(2))});")
            continue
        im = re.match(r"ADD INDEX\s+([a-zA-Z_0-9]+)\s*\((.*)\)", part, re.S)
        if im:
            outs.append(f"CREATE INDEX {im.group(1)} ON {tname} ({quote_cols(im.group(2))});")
            continue
        # unsupported: keep as alter
        outs.append(f"ALTER TABLE {tname} ADD {part};")
    return outs


def convert_alter_autoinc(stmt):
    """ALTER TABLE ... MODIFY id ... AUTO_INCREMENT, AUTO_INCREMENT=N;
    Returns sequence bootstrap statements or None."""
    m = re.match(r"ALTER TABLE\s+`?([a-zA-Z_0-9]+)`?\s+MODIFY\s+`?([a-zA-Z_0-9]+)`?.*?AUTO_INCREMENT(?:,\s*AUTO_INCREMENT=(\d+))?", stmt, re.S)
    if not m:
        return None
    tname, col = m.group(1), m.group(2)
    return [
        f"CREATE SEQUENCE IF NOT EXISTS {tname}_{col}_seq;",
        f"ALTER TABLE {tname} ALTER COLUMN \"{col}\" SET DEFAULT nextval('{tname}_{col}_seq');",
        f"SELECT setval('{tname}_{col}_seq', COALESCE((SELECT MAX(\"{col}\") FROM {tname}), 1));",
    ]


def main():
    with open(SRC, "r", encoding="utf-8", errors="replace") as f:
        sql = f.read()
    # remove mysql conditional comments
    sql = re.sub(r"/\*!\d+.*?\*/", "", sql, flags=re.S)
    sql = remove_comments(sql)
    stmts = split_statements(sql)

    creates = []
    inserts = []
    index_als = []
    fk_als = []
    seq_als = []

    for s in stmts:
        up = s.upper()
        if up.startswith("CREATE TABLE"):
            res = parse_create_table(s)
            if res:
                tname, cols = res
                creates.append("CREATE TABLE %s (\n%s\n);" % (tname, ",\n".join("  " + c for c in cols)))
            continue
        if up.startswith("INSERT INTO"):
            inserts.append(convert_insert(s))
            continue
        if up.startswith("ALTER TABLE"):
            if "MODIFY" in up and "AUTO_INCREMENT" in up:
                seq = convert_alter_autoinc(s)
                if seq:
                    seq_als.extend(seq)
                continue
            if "FOREIGN KEY" in up or "CONSTRAINT" in up:
                m = re.match(r"ALTER TABLE\s+`?([a-zA-Z_0-9]+)`?\s*(.*)$", s, re.S)
                if m and ("FOREIGN KEY" in m.group(2).upper()):
                    tname = m.group(1)
                    fbody = strip_backticks(m.group(2).rstrip(";"))
                    # quote constraint cols + references
                    fbody = re.sub(r"FOREIGN KEY \(([^)]*)\)", lambda mm: "FOREIGN KEY (" + quote_cols(mm.group(1)) + ")", fbody, flags=re.I)
                    fbody = re.sub(r"REFERENCES\s+([a-zA-Z_0-9]+)\s*\(([^)]*)\)", lambda mm: "REFERENCES %s (%s)" % (mm.group(1), quote_cols(mm.group(2))), fbody, flags=re.I)
                    for part in split_top(fbody):
                        fk_als.append(f"ALTER TABLE {tname} {part};")
                    continue
            # index block
            index_als.extend(convert_alter_index(s))
            continue
        # ignore SET/START TRANSACTION/COMMIT/etc
        continue

    with open(OUT, "w") as f:
        f.write("-- Converted from MySQL/MariaDB dump by migrate.py\n")
        f.write("-- Source: database/ibanking.sql\n\n")
        f.write("-- ============ TABLES ============\n")
        f.write("\n".join(creates))
        f.write("\n\n-- ============ DATA ============\n")
        f.write("\n".join(inserts))
        f.write("\n\n-- ============ PRIMARY KEYS & UNIQUE ============\n")
        f.write("\n".join(index_als))
        f.write("\n\n-- ============ SEQUENCES ============\n")
        f.write("\n".join(seq_als))
        f.write("\n\n-- ============ FOREIGN KEYS ============\n")
        f.write("\n".join(fk_als))
        f.write("\n")

    print(f"OK: {len(creates)} tables, {len(inserts)} inserts, {len(index_als)} index stmts, {len(fk_als)} fks, {len(seq_als)} seq stmts")


if __name__ == "__main__":
    main()
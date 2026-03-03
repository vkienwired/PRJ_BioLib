from docx import Document
import pandas as pd
import os
from io import BytesIO
from PIL import Image

WORD_FILE = r"C:\xampp\htdocs\BioLib\python\hop_chat_tu_nhien.docx"
OUTPUT_EXCEL = "output/hop_chat.xlsx"
IMAGE_DIR = "output/images"

os.makedirs(IMAGE_DIR, exist_ok=True)

doc = Document(WORD_FILE)
table = doc.tables[0]

columns = [
    "stt", "name", "cid", "smiles",
    "benefit", "weakness", "origin",
    "purpose", "doi"
]

def save_image(image_bytes, cid):
    img = Image.open(BytesIO(image_bytes))
    ext = img.format.lower()  # png / jpeg
    if ext == "jpeg":
        ext = "jpg"

    path = os.path.join(IMAGE_DIR, f"{cid}.{ext}")
    img.save(path)
    return path

rows = []

for row in table.rows[1:]:
    cells = [c.text.strip().replace("\n", " ") for c in row.cells]

    stt = cells[0]
    name = cells[1]
    cid = cells[2]
    benefit = cells[4]
    weakness = cells[5]
    origin = cells[6]
    purpose = cells[7]
    doi = cells[8]

    smiles = ""  # sẽ fill sau

    # Cột hình ảnh: index = 3
    cell = row.cells[3]
    tc = cell._tc
    drawings = tc.xpath(".//w:drawing")

    for d in drawings:
        blip = d.xpath(".//a:blip")
        if not blip:
            continue

        rid = blip[0].get(
            "{http://schemas.openxmlformats.org/officeDocument/2006/relationships}embed"
        )
        image_part = doc.part.related_parts[rid]
        save_image(image_part.blob, cid)

    rows.append([
        stt, name, cid, smiles,
        benefit, weakness, origin,
        purpose, doi
    ])

df = pd.DataFrame(rows, columns=columns)
df.to_excel(OUTPUT_EXCEL, index=False)

print("✅ Done: Word → Excel | images kept original, named by CID")
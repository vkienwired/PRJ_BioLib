import os
from rdkit import Chem
from rdkit.Chem import AllChem
from rdkit.Chem.Draw import rdMolDraw2D
from IPython.display import SVG
import argparse

parser = argparse.ArgumentParser(description="Process ligand files")
parser.add_argument('filename', nargs='+', help="Path to the ligand file (supports .sdf and .mol2)")
args = parser.parse_args()
def generate_and_save_svg(smiles, cid):

    IMG_DIR = "/var/www/html/mscskeylab.hus.vnu.edu.vn/rwdir/thach/"

    mol = Chem.MolFromSmiles(smiles)

    AllChem.Compute2DCoords(mol)
    rdMolDraw2D.PrepareMolForDrawing(mol)
    conformer = mol.GetConformer(0)

    drawer = rdMolDraw2D.MolDraw2DSVG(300, 300)

    drawer.DrawMolecule(mol, confId=0)

    drawer.FinishDrawing()

    svg_data = drawer.GetDrawingText()

    output_filename = os.path.join(IMG_DIR, "{}.svg".format(cid))

#    with open(output_filename, "w") as svg_file:
#        svg_file.write(svg_data)
    try:
       with open(output_filename, "w") as svg_file:
         svg_file.write(svg_data)
       print("SVG data has been saved to {}".format(output_filename))
    except IOError as e:
       print("Error saving SVG data to {}: {}".format(output_filename, e))
       exit(1)

#smiles = 'CC1(C(=O)N2C(C(=O)N3CCCC3C2(O1)O)CC4=CC=CC=C4)NC(=O)C5CC6C(CC7=CNC8=CC=CC6=C78)N(C5)C'
#cid = '101781'  
#generate_and_save_svg(smiles, cid)  
smiles = args.filename[0]
cid = args.filename[1]
generate_and_save_svg(smiles, cid)

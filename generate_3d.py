import sys
from rdkit import Chem
from rdkit.Chem import AllChem

def main():
    # Nhận 2 tham số: Chuỗi SMILES và Đường dẫn file lưu từ PHP
    if len(sys.argv) < 3:
        print("Error: Thiếu tham số đầu vào từ PHP!")
        sys.exit(1)

    smiles = sys.argv[1]
    output_path = sys.argv[2]

    try:
        # 1. Đọc cấu trúc từ chuỗi SMILES
        mol = Chem.MolFromSmiles(smiles)
        if mol is None:
            print("Error: Chuỗi SMILES không hợp lệ!")
            sys.exit(1)

        # 2. Thêm nguyên tử Hydro (Bắt buộc để tạo khung 3D chuẩn)
        mol = Chem.AddHs(mol)
        
        # 3. Tính toán không gian 3D ban đầu
        AllChem.EmbedMolecule(mol, AllChem.ETKDG())
        
        # 4. Tối ưu hóa năng lượng để phân tử có hình dáng tự nhiên nhất
        AllChem.MMFFOptimizeMolecule(mol)

        # 5. Ghi dữ liệu ra file định dạng .sdf
        writer = Chem.SDWriter(output_path)
        writer.write(mol)
        writer.close()
        
        # Trả về tín hiệu thành công cho PHP biết
        print("success")

    except Exception as e:
        print(f"Error: {str(e)}")

if __name__ == "__main__":
    main()
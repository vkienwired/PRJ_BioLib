import os
import cv2

def png_to_svg(input_path, output_path):
    img = cv2.imread(input_path)

    if img is None:
        print(f"Không đọc được ảnh: {input_path}")
        return

    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)

    # Làm mượt để giảm nhiễu
    blur = cv2.GaussianBlur(gray, (5, 5), 0)

    # Edge detection cho ảnh line-art
    edges = cv2.Canny(blur, 60, 180)

    contours, _ = cv2.findContours(
        edges,
        cv2.RETR_TREE,
        cv2.CHAIN_APPROX_NONE
    )

    height, width = gray.shape

    with open(output_path, "w", encoding="utf-8") as f:
        f.write(f'<svg xmlns="http://www.w3.org/2000/svg" '
                f'width="{width}" height="{height}" '
                f'viewBox="0 0 {width} {height}">\n')

        for contour in contours:

            # Lọc contour quá nhỏ (nhiễu)
            if cv2.contourArea(contour) < 5:
                continue

            # Làm gọn số điểm để bớt răng cưa
            epsilon = 0.003 * cv2.arcLength(contour, True)
            approx = cv2.approxPolyDP(contour, epsilon, True)

            f.write('<path d="')

            for i, point in enumerate(approx):
                x, y = point[0]
                if i == 0:
                    f.write(f'M {x} {y} ')
                else:
                    f.write(f'L {x} {y} ')

            f.write('" stroke="black" stroke-width="1" fill="none"/>\n')

        f.write('</svg>')

    print(f"Đã tạo: {output_path}")


def convert_folder(input_folder, output_folder):
    os.makedirs(output_folder, exist_ok=True)

    for file in os.listdir(input_folder):
        if file.lower().endswith(".png"):
            input_path = os.path.join(input_folder, file)
            output_name = os.path.splitext(file)[0] + ".svg"
            output_path = os.path.join(output_folder, output_name)

            png_to_svg(input_path, output_path)


# ==== CHỈNH 2 DÒNG NÀY ====
input_folder = "images"
output_folder = "output_svg"

convert_folder(input_folder, output_folder)
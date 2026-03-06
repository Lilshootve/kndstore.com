#!/usr/bin/env python3
"""Convert background-lab.png to optimized WebP (max 1920px, quality 70)."""
from pathlib import Path
from PIL import Image

MAX_WIDTH = 1920
QUALITY = 70  # 65-75 range
SRC = Path(__file__).resolve().parent.parent / "assets" / "images" / "background-lab.png"
DEST = Path(__file__).resolve().parent.parent / "assets" / "images" / "background-lab.webp"

def main():
    if not SRC.exists():
        print(f"Error: {SRC} not found")
        return 1
    img = Image.open(SRC)
    if img.mode in ("RGBA", "P"):
        img = img.convert("RGB")
    w, h = img.size
    if w > MAX_WIDTH:
        ratio = MAX_WIDTH / w
        new_h = int(h * ratio)
        img = img.resize((MAX_WIDTH, new_h), Image.Resampling.LANCZOS)
    img.save(DEST, "WEBP", quality=QUALITY, method=6)
    size_mb = DEST.stat().st_size / (1024 * 1024)
    print(f"Saved {DEST} ({size_mb:.2f} MB)")
    return 0

if __name__ == "__main__":
    exit(main())

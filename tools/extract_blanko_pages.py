from pathlib import Path
import pdfplumber

p = Path('assets/blanko/blanko tanda merah.pdf')
if not p.exists():
    print('MISSING', p)
    raise SystemExit(1)

with pdfplumber.open(str(p)) as pdf:
    for i, page in enumerate(pdf.pages, start=1):
        out = p.with_name(p.stem.replace(' ', '_') + f'_page{i}.png')
        try:
            img = page.to_image(resolution=200)
            img.save(str(out))
            print('saved', out)
        except Exception as e:
            print('error saving', out, e)

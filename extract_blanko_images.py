from pathlib import Path
import pdfplumber

for name in ['assets/blanko/blanko ori.pdf', 'assets/blanko/blanko tanda merah.pdf']:
    p = Path(name)
    print('FILE:', p)
    if not p.exists():
        print(' MISSING')
        continue
    with pdfplumber.open(str(p)) as pdf:
        print(' pages', len(pdf.pages))
        for i, page in enumerate(pdf.pages):
            try:
                out = p.with_name(f"{p.stem}_page{i+1}.png")
                img = page.to_image(resolution=150)
                img.save(str(out))
                print(' saved', out)
            except Exception as e:
                print(' image error', e)

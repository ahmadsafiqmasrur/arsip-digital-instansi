<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Arsip - <?= htmlspecialchars($no_arsip) ?></title>
    <style>
        html, body { margin: 0; padding: 0; height: 100%; }
        body { font-family: "Times New Roman", Times, serif; font-size: 9pt; line-height: 1.3; color: #000; }
        .page { position: relative; width: 100%; min-height: 100%; page-break-after: always; }
        .page:last-child { page-break-after: auto; }
        .blanko-background { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain; z-index: 0; }
        .overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1; }
        .field { position: absolute; font-size: 9pt; font-weight: bold; color: #000; white-space: nowrap;  }
        /* adjusted positions to align with red markers */
        .field.no-arsip { top: 6.5%; left: 50%; transform: translateX(-50%); }
        .field.no-akta { top: 32%; left: 40%; }
        .field.kecamatan { top: 41.5%; left: 35%; }
        .field.kabupaten { top: 41.5%; left: 65%; }
        .field.provinsi { top: 41.5%; left: 65%; }
        .field.republik { top: 41.5%; left: 65%; }
        .field.kewarganegaraan { top: 41.5%; left: 65%; }
        .field.agama { top: 41.5%; left: 65%; }
        
        /* temporary debug styling */
        .photo { position: absolute; width: 130px; height: 170px; overflow: hidden; border: 2px solid rgba(0,0,255,0.8); background: rgba(255,255,255,0.7); z-index: 20; }
        .photo img { width: 100%; height: 100%; object-fit: cover; }
        .photo.foto-suami { top: 70%; left: 18%; }
        .photo.foto-istri { top: 70%; left: 55%; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <?php if (!empty($blanko_pages) && is_array($blanko_pages)): ?>
        <?php foreach ($blanko_pages as $pageIndex => $blankoImg): ?>
            <div class="page">
                <img class="blanko-background" src="<?= $blankoImg ?>" />
                    <?php
                        $pageNum = $pageIndex + 1;
                        $positions = $blanko_positions[$pageNum] ?? [];
                    ?>
                    <?php if (!empty($positions)): ?>
                        <div class="overlay">
                            <?php foreach ($positions as $fname => $pos):
                                // resolve value from field_values map provided by controller
                                $val = '';
                                if (!empty($field_values) && array_key_exists($fname, $field_values)) {
                                    $val = $field_values[$fname];
                                }
                                $style = '';
                                if (!empty($pos['top'])) $style .= 'top:' . $pos['top'] . ';';
                                if (!empty($pos['left'])) $style .= 'left:' . $pos['left'] . ';';
                                if (!empty($pos['transform'])) $style .= 'transform:' . $pos['transform'] . ';';
                                if (strpos($fname, 'foto') === 0) {
                                    $w = !empty($pos['width']) ? $pos['width'] : '130px';
                                    $h = !empty($pos['height']) ? $pos['height'] : '170px';
                                    echo '<div class="photo ' . htmlspecialchars($fname) . '" style="' . $style . ' width:' . $w . '; height:' . $h . ';">';
                                    if (!empty($val)) {
                                        echo '<img src="' . htmlspecialchars($val) . '" />';
                                    }
                                    echo '</div>';
                                } else {
                                    echo '<div class="field ' . htmlspecialchars($fname) . '" style="' . $style . '">' . htmlspecialchars($val) . '</div>';
                                }
                            endforeach; ?>
                        </div>
                    <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>

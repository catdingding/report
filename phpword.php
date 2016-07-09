<?php 


use PhpOffice\PhpWord\Settings;

$writers = array('Word2007' => 'docx', 'ODText' => 'odt', 'RTF' => 'rtf', 'HTML' => 'html', 'PDF' => 'pdf');

function write($phpWord, $filename, $writers)
{
    $result = '';
    // Write documents
    foreach ($writers as $format => $extension) {
        $result .= date('H:i:s') . " Write to {$format} format";
        if (null !== $extension) {
            $targetFile = __DIR__ . "/results/{$filename}.{$extension}";
            $phpWord->save($targetFile, $format);
        } else {
            $result .= ' ... NOT DONE!';
        }
        $result .= EOL;
    }
    $result .= getEndingNotes($writers);
    return $result;
}

echo $phpWord = \PhpOffice\PhpWord\IOFactory::read('http://open.nantou.gov.tw/OpenFront/report/show_file.jsp?sysId=C105AI007&fileNo=1');



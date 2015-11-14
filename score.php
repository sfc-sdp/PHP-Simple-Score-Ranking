<?php
header('Content-type: text/plain; charset=utf-8');

$FILE_NAME = 'score.tsv';

$name = (string)filter_input(INPUT_GET, 'name');
$score = (int)filter_input(INPUT_GET, 'score', FILTER_VALIDATE_INT);

// tsv 読み込み
$src = file_get_contents($FILE_NAME);

// array 変換
$lines = explode("\n", $src);
if (!is_array($lines)) exit;
$data = array();
foreach ($lines as $line) {
	$line = trim($line);
	if (! empty($line)) {
		array_push( $data, explode("\t", $line) );
	}
}

// GETがある場合は
if (strlen($name) > 0 && $score > 0) {
	// 追加
	array_push( $data, array($score, $name) );

	// ソート
	array_multisort( array_column($data, 0), SORT_DESC, SORT_NUMERIC, $data );

	// 間引き
	while (count($data) > 10) {
		array_pop($data);
	}
}

// 表示
$tsv = "";
foreach ($data as $row) {
	$tsv .= join("\t", $row) . "\n";
}
echo $tsv;

// 保存
if (strlen($name) > 0 && $score > 0) {
	file_put_contents($FILE_NAME, $tsv);
}
?>

<?php
$url = 'https://olinda.bcb.gov.br/olinda/servico/PTAX/versao/v1/odata/CotacaoDolarPeriodo(dataInicial=@dataInicial,dataFinalCotacao=@dataFinalCotacao)?@dataInicial=\'' . date("m-d-Y", strtotime("-7 days")) . '\'&@dataFinalCotacao=\'' . date("m-d-Y") . '\'&$top=1&$orderby=dataHoraCotacao%20desc&$format=json&$select=cotacaoCompra,dataHoraCotacao';

$data = json_decode(file_get_contents($url), true);

$price = $data['value']['0']['cotacaoCompra'];
$date = $data['value']['0']['dataHoraCotacao'];

$currency_pattern = numfmt_create("pt-br", NumberFormatter::CURRENCY);

$currency_amount = $_REQUEST['amount_currency'] ?? 0;

$converted_quantity = $currency_amount / $price;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversor de Moeda</title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <main>
        <div>
            <h1>Conversor</h1>
            <form action="" method="get">
                <label for="amount_currency">Insira a Quantidade de Reais para Conversão: </label>
                <input type="number" name="amount_currency" id="amount_currency" value="<?php echo $currency_amount ?>" step="0.01">
                <input type="submit" value="Converter">
                <p>
                    <?php echo numfmt_format_currency($currency_pattern, $currency_amount, "BRL") ?> equivalem à <strong><?php echo numfmt_format_currency($currency_pattern, $converted_quantity, "USD") ?></strong> conforme contação de <?php echo numfmt_format_currency($currency_pattern, $price, "USD") ?> para cada Real(BRL), obtida em <?php echo date ('d / m / Y', strtotime($date)) ?> por meio do <a href="https://www.bcb.gov.br/"><strong>Banco Central do Brasil</strong></a>
                    Conversão realizada em: <?php echo date ('d / m / Y') ?> às <?php echo date ('H:i')?>.
                </p>
            </form>
        </div>
    </main>
</body>
</html>
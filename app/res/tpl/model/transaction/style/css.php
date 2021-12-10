body, pre, h1, h2, h3 {
    font-family: sans-serif;
    font-size: 9pt;
}
p, ul, ol {
    margin: 2.5mm 0;
    padding: 0;
}
.payment-conditions {
}
.pagefooter,
.senderline {
    color: #333333;
    font-size: 6pt;
}
.senderline {
    border-bottom: 0.1mm solid #333333;
}
.pagefooter.legal {
    font-size: 4pt;
    text-align: center;
}
tr.alternative th,
tr.alternative td {
    font-style: italic;
    color: #666666;
}
tr.subtotal td,
tr.freetext td,
tr.lofty td {
    padding: 1mm 0;
}
.name,
.postal,
.postal-address {
    font-size: 10pt;
}
.bold {
    font-weight: bold;
}
.emphasize {
    font-weight: bold;
    font-size: 11pt;
}
.uberemphasize {
    font-size: 12pt;
    font-weight: bold;
}
.dinky {
    font-size: 8pt;
}
.moredinky {
    font-size: 6pt;
}
.centered {
    text-align: center;
}
table {
    border-collapse: collapse;
}
th, td {
    vertical-align: top;
}
th {
    text-align: left;
}
th.bt,
td.bt {
    border-top: 0.1mm solid #000000;
}
th.br,
td.br {
    border-right: 0.1mm solid #000000;
}
th.bb,
td.bb {
    border-bottom: 0.1mm solid #000000;
}
th.number,
td.number {
    text-align: right;
}
.cushion-right {
    padding-right: 2mm;
}
table.info td.label,
table.info td.value {
    text-align: right;
}
table.pageheader td.label,
table.pageheader td.value {
    font-size: 6pt;
}
table.pageheader td.label {
    text-align: right;
}
table.pageheader td.value {
    text-align: left;
}
.page-break {
    page-break-after: always;
}
@page {
    header: ksmheader;
    footer: ksmfooter;
    margin-left: 20mm;
}
@page :first {
    header: ksmheader-firstpage;
    footer: ksmfooter-firstpage;
    margin-bottom: 4cm;
}

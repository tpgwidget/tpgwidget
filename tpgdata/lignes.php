<?php

function lineColor($l) {
    switch ($l) {
        case '1':return '663399';case '2':return 'CCCC33';case '3':return 'CC3399';case '4':return 'CC0033';case '5':case'NV':return '0099FF';case '6':return '0099CC';case '7':return '009933';case '8':return '993333'; case '9':return 'CC0033';
        case '10':return '006633';case '11':return '993399';case '12':return 'FF9900';case '14':return '663399';case '15':return '993333';case '18':return 'CC3399';case '19':return 'FFCC00';case '21':return '663333';case '22':return '663399';case '23':return 'CC3399';case '25':return '805A28';case '27':return '009DBC';case '28':return 'FFCC00';case '31':return '009999';case '32':return '666666';case '33':return '009999';case '34':return '99CCCC';
        case '35':case '36':case 'XA':return '666666';case '41':return '009999';case '42':return '99CCCC';case '43':return '99CCCC';case '44':return '009999';case '45':return '99CCCC';case '46':return '009999';case '47':return '00B0A4';case '49':return '009999';
        case '51':return '009999';case '53':return '99CCCC';case '54':return '009999';case '56':return '009999';case '57':return '99CCCC';case '61':return 'FF9BAA';case '62':return 'EB6CA3';case '63':return 'FF9BAA';
        case 'A':return 'FF6600';case 'B':return 'FF9999';case 'C':return 'FF6600';case 'D':return 'FF9999';case 'N':return 'FF9999';case 'DN':return 'FF9BAA';case 'E':return 'FF6600';case 'F':return 'FF9999';
        case 'G':return 'FF9999';case 'K':return 'FF9999';case 'L': case 'J': return 'FF6600';case 'M':return 'FF9BAA';case 'NA':return '5A1E82';case 'NC':return '5E3285';case 'ND':return '84471C';
        case 'NE':return 'B82F89';case 'NJ':return 'D2DB4A';case 'NK':return 'F5A300';case 'NM':return 'F5A300';case 'NO':return 'B82F89';case 'NP':return '00B0A4';case 'NS':return '008CBE';case 'NT':return '00ACE7';
        case 'O':return 'FF9BAA';case 'S':return '003399';case 'T':return 'FF9BAA';case 'TO':return 'E2001D';case 'TT':return 'FD0000';case 'V':return 'FF6600';case 'W':return '003399';case 'X':return '003399'; case 'U': case 'P': return '003399'; case 'Y':return 'FF9999';case 'Z':return 'FF9999';
        case '5+': case 'C+': case 'G+': case 'V+': return '000';
    default: return 'f60';
    }
}

$lignesAvecTexteNoir = [2,12,19,28,34,42,43,45,53,57,61,63, "B", "Z","Y","T","O","K","G","F","M","D","DN","Dn","NK","NM","NJ",'N'];
$lignesAvecTexteBlanc = ['U', 'NV', '1', '3', '4', '5', '6', '7', '8', '9', '10', '11', '14', '15', '18', '21', '22', '23', '25', '31', '32', '33', '35', '36', '41', '44', '46', '47', '51', '52', '54', '56', 'A', 'C', 'E', 'J', 'L', 'NA', 'NC', 'ND', 'NE', 'NO', 'NP', 'NS', 'NT', 'P', 'S', 'TO', 'TT', 'V', 'W', 'X', 'G+', '5+', 'V+', 'C+', 'XA', '62'];

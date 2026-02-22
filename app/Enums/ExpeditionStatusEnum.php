<?php

namespace App\Enums;

enum ExpeditionStatusEnum: string
{
    case analise = 'ANALISE';
    case rejeitada = 'REJEITADA';
    case autorizada = 'AUTORIZADA';
}
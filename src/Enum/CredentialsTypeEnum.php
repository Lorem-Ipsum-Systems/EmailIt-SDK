<?php

namespace LoremIpsum\EmailIt\Enum;

enum CredentialsTypeEnum: string
{
    case SMTP = "smtp";
    case API = "api";
}

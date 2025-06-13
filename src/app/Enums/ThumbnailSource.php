<?php

namespace App\Enums;

enum ThumbnailSource: string
{
    case Default = 'default';
    case AutoLoad = 'autoLoad';
    case UserLoad = 'userLoad';
    case Gstatic = 'gstatic';
}

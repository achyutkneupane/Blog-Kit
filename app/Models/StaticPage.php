<?php

declare(strict_types=1);

namespace App\Models;

use AchyutN\LaravelHelpers\Traits\HasTheSlug;
use App\Traits\HasSEO;
use Illuminate\Database\Eloquent\Model;

final class StaticPage extends Model
{
    use HasSEO, HasTheSlug;
}

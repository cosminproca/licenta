<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Urgent()
 * @method static static High()
 * @method static static Medium()
 * @method static static Low()
 */
final class TaskPriorityType extends Enum
{
    public const Urgent = 'urgent';
    public const High = 'high';
    public const Medium = 'medium';
    public const Low = 'low';
}

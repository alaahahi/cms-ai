<?php

namespace App\Enums;

enum ContactStatus: int
{
    case Unassigned = 0;
    case Assigned = 1;
    case OfferAccepted = 2;
    case OfferRejected = 3;
    case FollowUp = 4;
    case Busy = 5;
    case Unknown = 6;

    public function label(): string
    {
        return match($this) {
            self::Unassigned => 'Unassigned',
            self::Assigned => 'Assigned',
            self::OfferAccepted => 'Offer Accepted',
            self::OfferRejected => 'Offer Rejected',
            self::FollowUp => 'Follow Up',
            self::Busy => 'Busy',
            self::Unknown => 'Unknown',
        };
    }
}

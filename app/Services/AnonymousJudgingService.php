<?php

namespace App\Services;

use App\Models\Event;
use App\Models\User;
use App\Models\AnonymousJudgeMapping;

class AnonymousJudgingService
{
    public static function generateAnonymousCode(Event $event, User $judge)
    {
        $judgeNumber = AnonymousJudgeMapping::where('event_id', $event->id)->count() + 1;
        $code = 'JUDGE-' . strtoupper(chr(65 + ($judgeNumber - 1))) . str_pad($judgeNumber, 3, '0', STR_PAD_LEFT);

        return AnonymousJudgeMapping::firstOrCreate(
            ['event_id' => $event->id, 'judge_id' => $judge->id],
            ['anonymous_code' => $code]
        );
    }

    public static function getAnonymousCode(Event $event, User $judge)
    {
        return AnonymousJudgeMapping::where('event_id', $event->id)
            ->where('judge_id', $judge->id)
            ->value('anonymous_code');
    }

    public static function getJudgeFromCode(Event $event, $code)
    {
        return AnonymousJudgeMapping::where('event_id', $event->id)
            ->where('anonymous_code', $code)
            ->first()?->judge;
    }

    public static function maskParticipantName(User $participant, Event $event)
    {
        if (!$event->anonymous_judging) {
            return $participant->name;
        }

        $participantNumber = User::where('id', '<=', $participant->id)
            ->where('role', 'participant')
            ->count();

        return 'Participant-' . str_pad($participantNumber, 3, '0', STR_PAD_LEFT);
    }
}
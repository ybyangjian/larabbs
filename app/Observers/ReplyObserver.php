<?php

namespace App\Observers;

use App\Models\Reply;
use App\Notifications\TopicReplied;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function created(Reply $reply)
    {
        //统计回复数
        $topic = $reply->topic;
        $topic->increment('reply_count', 1);

        // 如果评论的作者不是话题的作者，才需要通知
        if ( !$reply->user->isAuthorOf($topic)){
            $topic->user->notify(new TopicReplied($reply));
        }
    }


    public function creating(Reply $reply)
    {
        //防止XSS注入攻击
        $reply->content = clean($reply->content,'user_topic_body');
    }
}
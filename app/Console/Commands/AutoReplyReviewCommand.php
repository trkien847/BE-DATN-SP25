<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reviews;
use Carbon\Carbon;

class AutoReplyReviewCommand extends Command
{
    protected $signature = 'reviews:auto-reply';
    protected $description = 'Tự động trả lời các đánh giá chưa được phản hồi sau 1 ngày';

    public function handle()
    {
        $reviews = Reviews::whereNull('admin_reply')
            ->where('created_at', '<=', Carbon::now()->subDay())
            ->get();

        foreach ($reviews as $review) {
            $reply = $this->generateReplyBasedOnRating($review->rating);
            
            $review->update([
                'admin_reply' => $reply,
                'replied_at' => now(),
                'is_auto' => true
            ]);

            $this->info("Đã tự động trả lời đánh giá ID: {$review->id}");
        }
    }

    private function generateReplyBasedOnRating($rating)
    {
        return match ($rating) {
            5 => 'Cảm ơn bạn đã đánh giá tích cực! Chúng tôi rất vui khi sản phẩm đáp ứng được mong đợi của bạn.',
            4 => 'Cảm ơn bạn đã đánh giá! Chúng tôi sẽ tiếp tục cải thiện để phục vụ bạn tốt hơn.',
            3 => 'Cảm ơn bạn đã đánh giá. Chúng tôi rất tiếc vì chưa làm bạn hoàn toàn hài lòng và sẽ cố gắng cải thiện.',
            2 => 'Chúng tôi xin lỗi vì trải nghiệm chưa tốt của bạn. Mong bạn cho chúng tôi cơ hội cải thiện trong lần tới.',
            1 => 'Chúng tôi thành thật xin lỗi vì trải nghiệm không tốt. Vui lòng liên hệ với chúng tôi để được hỗ trợ tốt hơn.',
            default => 'Cảm ơn bạn đã đánh giá sản phẩm!'
        };
    }
}
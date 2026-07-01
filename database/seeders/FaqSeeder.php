<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run()
    {
        $faqs = [
            [
                'category'   => 'Ordering',
                'sort_order' => 1,
                'question'   => 'How do I place an order on SepoSale?',
                'answer'     => 'You can place an order directly through our website. Browse our product catalogue, add items to your cart, and proceed to checkout. You will need to create an account or log in to complete your purchase. Once your order is placed, our team will confirm it and arrange delivery.',
            ],
            [
                'category'   => 'Ordering',
                'sort_order' => 2,
                'question'   => 'What is the minimum order quantity?',
                'answer'     => 'Minimum order quantities vary by product. For cement, the minimum order is typically 10 bags. For quarry stone, river sand, and other aggregates, the minimum is 1 trip (approximately 5 tonnes). There is no minimum order for cement blocks — you can order any quantity that suits your project.',
            ],
            [
                'category'   => 'Ordering',
                'sort_order' => 3,
                'question'   => 'Can I get a quotation before ordering?',
                'answer'     => 'Yes. You can request a formal quotation through our website by visiting the "Request Quotation" page. Fill in your product requirements, quantities, and delivery location, and our team will send you a detailed quotation within one business day.',
            ],
            [
                'category'   => 'Products',
                'sort_order' => 4,
                'question'   => 'What types of cement do you sell?',
                'answer'     => 'We supply a range of cement grades suitable for residential and commercial construction, including ordinary Portland cement (OPC) and Portland Pozzolana cement (PPC). Our cement is sourced from trusted manufacturers including Lafarge Malawi and Shayona Cement. Check our product pages for current available grades and pack sizes.',
            ],
            [
                'category'   => 'Products',
                'sort_order' => 5,
                'question'   => 'How do I know which aggregate is right for my project?',
                'answer'     => 'The right aggregate depends on your construction use. Quarry stone is ideal for foundations and structural concrete. Pebble stone works well for decorative finishes and drainage. River sand is used for plastering and fine concrete mixes. Quarry dust is suitable as a fine filler in block-making and road base work. If you are unsure, contact our team for guidance.',
            ],
            [
                'category'   => 'Delivery',
                'sort_order' => 6,
                'question'   => 'Do you deliver to my area?',
                'answer'     => 'We deliver to most areas across Malawi. Delivery availability and cost depend on your location. During checkout, you can enter your site location and our system will calculate the applicable delivery charge based on the distance and vehicle type required. For remote areas, please contact us directly to confirm availability.',
            ],
            [
                'category'   => 'Delivery',
                'sort_order' => 7,
                'question'   => 'How long does delivery take?',
                'answer'     => 'Delivery timelines depend on your location and product availability. For orders within Lilongwe and Blantyre, we typically deliver within 1–3 business days. For other regions, delivery may take 3–7 business days. You will receive a delivery confirmation once your order is dispatched. You can also track your order status from your account dashboard.',
            ],
            [
                'category'   => 'Payment',
                'sort_order' => 8,
                'question'   => 'What payment methods do you accept?',
                'answer'     => 'We accept payments via Standard Bank online payment gateway (debit/credit card) and direct bank transfer. For bank transfers, you will need to upload your proof of payment during checkout. Our team will confirm receipt and process your order once payment is verified. We do not currently accept cash on delivery.',
            ],
            [
                'category'   => 'Payment',
                'sort_order' => 9,
                'question'   => 'How do rewards work?',
                'answer'     => 'Customers who have spent over MWK 5,000,000 in total purchases on SepoSale are eligible to earn cash rewards. Rewards are attached to specific products and are earned proportionally with each payment you make. Your reward balance is visible on your account dashboard and will accumulate over time. Contact us to learn how to redeem your rewards.',
            ],
            [
                'category'   => 'Returns',
                'sort_order' => 10,
                'question'   => 'What is your returns and refund policy?',
                'answer'     => 'Due to the nature of building materials, we are unable to accept returns of opened or used products. For defective or incorrectly delivered goods, please contact us within 48 hours of delivery with photos and your order details. Our team will assess the situation and arrange a replacement, credit, or refund as appropriate. Approved refunds are processed within 5–7 business days.',
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create(array_merge($faq, ['active' => true]));
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Policy;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PolicyTableSeeder extends Seeder
{
    public function run()
    {
        $policies = [
            [
                'title' => 'Terms of Service',
                'sort_order' => 1,
                'body' => <<<'HTML'
<p>These Terms of Service govern your use of the SepoSale platform. By placing an order or creating an account, you agree to these terms.</p>

<h2>Orders &amp; Pricing</h2>
<p>All prices are listed in Malawi Kwacha (MWK) and are subject to change without notice. "Transport Inclusive" pricing covers delivery within our standard delivery zones.</p>

<h2>Payments</h2>
<p>We accept online card payments via Standard Bank and offline bank transfers. Orders paid by bank transfer are confirmed once proof of payment is verified.</p>

<h2>Delivery</h2>
<p>Delivery times are estimates and may vary based on location, vehicle availability and weather conditions.</p>

<h2>Rewards Programme</h2>
<p>Cash rewards are earned on qualifying orders and may be redeemed at checkout, subject to programme terms which may change from time to time.</p>

<h2>Limitation of Liability</h2>
<p>SepoSale is not liable for delays caused by circumstances beyond our reasonable control.</p>

<h2>Contact Us</h2>
<p>Questions about these terms? Contact us at hello@seposale.com.</p>
HTML,
            ],
            [
                'title' => 'Privacy Policy',
                'sort_order' => 2,
                'body' => <<<'HTML'
<p>SepoSale ("we", "us", "our") respects your privacy. This policy explains what information we collect when you use our platform, how we use it, and the choices you have.</p>

<h2>Information We Collect</h2>
<p>We collect information you provide directly, such as your name, WhatsApp phone number, delivery address and order details, as well as information generated automatically when you browse or place an order.</p>

<h2>How We Use Your Information</h2>
<p>We use your information to process orders and deliveries, communicate with you about your account and orders, improve our products and services, and comply with legal obligations.</p>

<h2>Sharing Your Information</h2>
<p>We do not sell your personal information. We may share it with delivery partners and payment processors strictly to fulfil your orders.</p>

<h2>Your Rights</h2>
<p>You may request access to, correction of, or deletion of your personal data at any time by contacting us.</p>

<h2>Contact Us</h2>
<p>If you have questions about this policy, contact us at info@seposale.com.</p>
HTML,
            ],
            [
                'title' => 'Delivery Policy',
                'sort_order' => 3,
                'body' => <<<'HTML'
<p>This Delivery Policy outlines the terms and procedures governing the delivery of products supplied by Seposale.com. By placing an order with Seposale, customers agree to the terms set out in this policy.</p>

<h2>1. Delivery Coverage</h2>
<p>Seposale provides delivery services within Malawi and may extend deliveries to other locations upon request and approval. Delivery availability depends on accessibility, road conditions, and logistical considerations.</p>

<h2>2. Delivery Scheduling</h2>
<p>Delivery dates and times are estimates provided in good faith. While Seposale strives to meet agreed schedules, delivery times are not guaranteed and may be affected by traffic, weather conditions, vehicle breakdowns, supplier delays, public holidays, or other unforeseen circumstances.</p>

<h2>3. Delivery Charges</h2>
<p>Delivery charges are determined based on distance, location, order size, vehicle requirements, fuel costs, and site accessibility. Delivery charges will be communicated to the customer before order confirmation.</p>

<h2>4. Customer Delivery Information</h2>
<p>Customers are responsible for providing accurate delivery addresses, contact details, site directions, and any information necessary to facilitate successful delivery. Seposale shall not be liable for delays or additional costs resulting from incorrect information.</p>

<h2>5. Site Accessibility</h2>
<p>Customers must ensure that delivery vehicles can safely access the delivery location. Where roads or site conditions are unsuitable, Seposale may refuse delivery, reschedule delivery, or require alternative unloading arrangements.</p>

<h2>6. Presence During Delivery</h2>
<p>Customers are encouraged to be present during delivery. If unavailable, they must appoint a representative authorized to inspect, receive, and acknowledge receipt of the materials on their behalf.</p>

<h2>7. Inspection Upon Delivery</h2>
<p>Before offloading, customers or their representatives must inspect and verify the type, quality, quantity, and condition of all materials delivered. Any discrepancies must be reported immediately to the delivery personnel before unloading begins.</p>

<h2>8. Offloading and Acceptance</h2>
<p>Offloading of materials constitutes acceptance of the delivery. Once materials have been offloaded and accepted at the delivery site, they shall be deemed correct and satisfactory unless otherwise documented before unloading.</p>

<h2>9. No Returns After Offloading</h2>
<p>Seposale does not accept returns, exchanges, or refunds for materials after they have been offloaded and accepted at the delivery location.</p>

<h2>10. Failed Deliveries</h2>
<p>Where delivery cannot be completed due to customer absence, inaccessible sites, refusal to receive materials, or other customer-related reasons, additional delivery charges may apply for rescheduled deliveries.</p>

<h2>11. Risk and Ownership</h2>
<p>Risk in the materials passes to the customer upon delivery and acceptance at the designated delivery location. Ownership of products transfers in accordance with applicable payment terms.</p>

<h2>12. Delays Beyond Our Control</h2>
<p>Seposale shall not be liable for delivery delays caused by events beyond its reasonable control, including extreme weather, civil disturbances, government actions, fuel shortages, labor disputes, or supplier disruptions.</p>

<h2>13. Health and Safety</h2>
<p>Customers must ensure a safe environment for drivers, delivery personnel, equipment, and vehicles. Seposale reserves the right to suspend or refuse deliveries where safety risks are identified.</p>

<h2>14. Complaints and Claims</h2>
<p>Any delivery-related complaints should be reported promptly. Customers should provide relevant details, photographs where applicable, and supporting documentation to assist with investigations.</p>

<h2>15. Policy Updates</h2>
<p>Seposale reserves the right to amend this Delivery Policy at any time. Updated versions will be published on Seposale.com.</p>

<h2>Important Customer Notice</h2>
<p>To ensure complete satisfaction, customers must verify the type, quality, quantity, and condition of materials before offloading. If the customer is unavailable, an authorized representative must inspect and receive the materials on their behalf. Materials cannot be returned, exchanged, or refunded after offloading and acceptance at the delivery site.</p>
HTML,
            ],
            [
                'title' => 'Returns & Refunds Policy',
                'sort_order' => 4,
                'body' => <<<'HTML'
<p>This policy governs returns, exchanges and refunds.</p>

<h2>Inspection</h2>
<p>Customers or authorised representatives must inspect materials before offloading.</p>

<h2>No Returns After Offloading</h2>
<p>Materials accepted and offloaded by the customer or their representative cannot be returned, exchanged or refunded.</p>

<h2>Eligible Returns</h2>
<p>Only before offloading where incorrect, damaged or short-delivered goods are supplied by Seposale.</p>

<h2>Transport Costs</h2>
<p>If a customer returns products where transport was not included in the original sale, the customer remains responsible for all transport and logistics costs for the return.</p>

<h2>Card Payment Refunds</h2>
<p>Approved refunds for payments made by card will be subject to a 3% deduction to recover non-refundable payment processing fees charged by payment systems.</p>

<h2>Refund Processing</h2>
<p>Refunds are processed after inspection and approval and may exclude non-recoverable charges.</p>

<h2>Limitation</h2>
<p>Liability is limited to the purchase price of approved returned goods.</p>
HTML,
            ],
        ];

        foreach ($policies as $policy) {
            Policy::updateOrCreate(
                ['slug' => Str::slug($policy['title'])],
                array_merge($policy, [
                    'slug' => Str::slug($policy['title']),
                    'active' => true,
                ])
            );
        }
    }
}

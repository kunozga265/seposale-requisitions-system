<?php

namespace Database\Seeders;

use App\Models\BuildingTip;
use Illuminate\Database\Seeder;

class BuildingTipSeeder extends Seeder
{
    public function run()
    {
        $tips = [
            [
                'title'    => 'How to Mix Cement for Strong Foundations',
                'category' => 'Foundation',
                'photo'    => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=800&q=80',
                'body'     => '<p>A strong foundation starts with the right cement mix. For structural foundations, a 1:2:4 mix ratio (1 part cement, 2 parts sand, 4 parts aggregate) is widely recommended for Malawian conditions.</p><p>Always use clean water — contaminated water weakens the bond and reduces strength. Add water gradually and mix thoroughly until you achieve a uniform consistency. The mix should be stiff enough to hold its shape but workable enough to pour and compact. Avoid adding too much water as this reduces the final strength of the concrete.</p><p>For critical structural elements, consider using ordinary Portland cement (OPC 42.5) and consult a qualified engineer for mix designs suited to your specific load requirements.</p>',
            ],
            [
                'title'    => 'Laying Cement Blocks: Tips for Even Walls',
                'category' => 'Masonry',
                'photo'    => 'https://images.unsplash.com/photo-1590599145008-e4ec48675bc4?w=800&q=80',
                'body'     => '<p>Even, well-aligned walls begin with a properly levelled first course. Use a spirit level and string line to ensure your base row is perfectly horizontal before building upward.</p><p>Apply mortar (1 part cement, 3 parts sand) in an even layer, typically 10–12mm thick. Press each block firmly into place and check for level and plumb as you go. Stagger your joints — never align vertical joints in adjacent courses, as this creates weak points in the wall.</p><p>Keep your blocks damp before laying them, especially in hot weather. Dry blocks absorb water from the mortar too quickly, weakening the bond. Cure completed walls by spraying with water for at least 7 days to allow full strength development.</p>',
            ],
            [
                'title'    => 'Plastering Walls: Getting a Smooth Finish',
                'category' => 'Plastering',
                'photo'    => 'https://images.unsplash.com/photo-1581094271901-8022df4466f9?w=800&q=80',
                'body'     => '<p>Good plastering requires proper surface preparation. Dampen the wall surface before applying the scratch coat — this prevents the plaster from drying too fast and cracking. Apply a scratch coat of 1:3 cement-sand mortar and score the surface while it is still green to give the finish coat something to bond with.</p><p>River sand is the best choice for plastering as it produces a smoother, finer finish than quarry dust. Sieve your sand to remove large particles or lumps that will affect the finish surface.</p><p>Apply the finish coat in thin, even strokes using a straight-edged float, working from bottom to top. Keep the surface moist for 3–5 days after plastering to allow proper curing and avoid shrinkage cracks.</p>',
            ],
            [
                'title'    => 'Choosing the Right Aggregate for Your Project',
                'category' => 'Materials',
                'photo'    => 'https://images.unsplash.com/photo-1508450859948-4e04fabaa4ea?w=800&q=80',
                'body'     => '<p>Different aggregates serve different purposes in construction. Understanding which to use where can save you money and ensure structural integrity.</p><p><strong>Quarry Stone</strong> (crushed stone) is ideal for structural concrete — foundations, columns, beams, and slabs. Its angular shape provides excellent interlocking and high compressive strength.</p><p><strong>River Sand</strong> is fine, rounded, and washed — perfect for plastering, screed, and fine concrete mixes. Its smoothness makes it easy to work with and produces clean finishes.</p><p><strong>Quarry Dust</strong> is a fine by-product of stone crushing. Use it in block-making or as a partial substitute for river sand in non-critical applications. It is not recommended as the sole fine aggregate in structural concrete.</p><p><strong>Pebble Stone</strong> works well in decorative finishes, garden paths, drainage layers, and non-structural backfill.</p>',
            ],
            [
                'title'    => 'How to Cure Concrete for Maximum Strength',
                'category' => 'Concrete',
                'photo'    => 'https://images.unsplash.com/photo-1559628229-5b8b59ddf7a4?w=800&q=80',
                'body'     => '<p>Curing is one of the most overlooked steps in construction, yet it has a major impact on concrete strength. Concrete gains strength through a chemical process called hydration — this requires moisture, and if the surface dries out too soon, the process stops prematurely.</p><p>Begin curing as soon as the concrete surface is firm enough to resist damage — usually within a few hours of pouring. Cover slabs and walls with wet hessian, plastic sheeting, or wet sand to retain moisture.</p><p>Keep the concrete moist for a minimum of 7 days for ordinary Portland cement, and up to 14 days for blended cements. In hot, dry, or windy conditions, increase the frequency of wetting. Properly cured concrete can achieve up to 30% more strength than uncured concrete of the same mix.</p>',
            ],
            [
                'title'    => 'Waterproofing Your Foundation Before Backfilling',
                'category' => 'Foundation',
                'photo'    => 'https://images.unsplash.com/photo-1505798577917-a65157d3320a?w=800&q=80',
                'body'     => '<p>Moisture penetration through foundations is one of the leading causes of structural deterioration and damp problems in Malawian homes. Waterproofing your foundation walls before backfilling is a small cost that can prevent significant long-term damage.</p><p>Apply a bituminous waterproofing membrane or cement-based waterproof coating to the external face of your foundation walls. Ensure the surface is clean, free of dust, and lightly dampened before application. Apply two coats for best results, allowing the first coat to dry before applying the second.</p><p>At the base of the foundation, install a drainage layer of clean gravel or crushed stone to direct groundwater away from the structure. A properly waterproofed foundation extends the life of your building significantly.</p>',
            ],
            [
                'title'    => 'Estimating How Much Cement You Need',
                'category' => 'Cement',
                'photo'    => 'https://images.unsplash.com/photo-1565008447742-97f6f38c985c?w=800&q=80',
                'body'     => '<p>Estimating cement quantities accurately helps you budget correctly and avoid costly shortages or wastage mid-project.</p><p>For a 1:2:4 concrete mix, you will need approximately 6–7 bags of cement (50kg each) per cubic metre of concrete. For a strip foundation 600mm wide, 300mm deep, running 10 metres, you need about 1.8m³ of concrete — roughly 11–13 bags.</p><p>For block laying mortar (1:3 mix), estimate 1 bag of cement per 50–60 standard blocks. For plastering at 12mm thickness (1:3 mix), 1 bag covers approximately 8–10m² of wall surface.</p><p>Always add a 10% contingency to your estimate to account for wastage, spillage, and rework. It is better to have slightly more cement on site than to pause construction waiting for a reorder.</p>',
            ],
            [
                'title'    => 'Roof Beam Preparation: What You Need to Know',
                'category' => 'Structural',
                'photo'    => 'https://images.unsplash.com/photo-1516156008625-3a9d6067fab5?w=800&q=80',
                'body'     => '<p>Ring beams (also called wall plates or bond beams) tie your walls together and distribute the load of the roof evenly. Skipping or under-designing this element is a common mistake with serious structural consequences.</p><p>A standard residential ring beam is typically 225mm wide × 225mm deep, reinforced with 4 bars of Y12 steel with R8 stirrups at 200mm centres. The concrete mix should be a minimum of 1:1.5:3 (1 cement : 1.5 sand : 3 stone).</p><p>Ensure the formwork is well-braced before pouring and vibrate the concrete to eliminate air pockets. Allow the ring beam to cure for at least 7 days before removing the shuttering and placing any roof loads on it.</p>',
            ],
        ];

        foreach ($tips as $tip) {
            BuildingTip::create(array_merge($tip, ['active' => true]));
        }
    }
}

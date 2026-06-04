<?php
// ─── Perla Vita — Products Database ───────────────────────────

function getProducts() {
  return [
    // ─── NECKLACES ─────────────────────────────────────────
    ['id'=>1,  'name'=>'Lumière Pendant',          'category'=>'necklaces','price'=>385, 'icon'=>'✦', 'rating'=>5, 'badge'=>'Top Rated', 'material'=>'18k Gold'],
    ['id'=>2,  'name'=>'Pearl Cascade Necklace',   'category'=>'necklaces','price'=>520, 'icon'=>'⬟', 'rating'=>5, 'badge'=>'Bestseller', 'material'=>'Sterling Silver & Pearls'],
    ['id'=>3,  'name'=>'Soleil Choker',            'category'=>'necklaces','price'=>295, 'icon'=>'◈', 'rating'=>4, 'badge'=>'', 'material'=>'18k Rose Gold'],
    ['id'=>4,  'name'=>'Étoile Chain',             'category'=>'necklaces','price'=>440, 'icon'=>'✧', 'rating'=>5, 'badge'=>'New', 'material'=>'Diamond & Gold'],
    ['id'=>5,  'name'=>'Velvet Strand',            'category'=>'necklaces','price'=>310, 'icon'=>'⬡', 'rating'=>4, 'badge'=>'', 'material'=>'White Gold'],
    ['id'=>6,  'name'=>'Aurore Layered Set',       'category'=>'necklaces','price'=>695, 'icon'=>'✦', 'rating'=>5, 'badge'=>'Top Rated', 'material'=>'18k Gold Set of 3'],
    ['id'=>7,  'name'=>'Midnight Sapphire Drop',   'category'=>'necklaces','price'=>890, 'icon'=>'◇', 'rating'=>5, 'badge'=>'Exclusive', 'material'=>'Blue Sapphire & Gold'],
    ['id'=>8,  'name'=>'Délicate Bar Necklace',    'category'=>'necklaces','price'=>265, 'icon'=>'━', 'rating'=>4, 'badge'=>'', 'material'=>'14k Gold Vermeil'],
    ['id'=>9,  'name'=>'Rivière Diamond Collar',   'category'=>'necklaces','price'=>2850,'icon'=>'◆', 'rating'=>5, 'badge'=>'Luxury', 'material'=>'Diamonds & Platinum'],
    ['id'=>10, 'name'=>'Emerald Vine Pendant',     'category'=>'necklaces','price'=>1200,'icon'=>'◈', 'rating'=>5, 'badge'=>'New', 'material'=>'Emerald & 18k Gold'],
    ['id'=>11, 'name'=>'Gossamer Lariat',          'category'=>'necklaces','price'=>340, 'icon'=>'⬜', 'rating'=>4, 'badge'=>'', 'material'=>'Sterling Silver'],
    ['id'=>12, 'name'=>'Baroque Pearl Rope',       'category'=>'necklaces','price'=>475, 'icon'=>'⬟', 'rating'=>5, 'badge'=>'Bestseller', 'material'=>'Baroque Pearls'],
    ['id'=>13, 'name'=>'Ruby Heart Pendant',       'category'=>'necklaces','price'=>980, 'icon'=>'♡', 'rating'=>5, 'badge'=>'Top Rated', 'material'=>'Ruby & Rose Gold'],
    ['id'=>14, 'name'=>'Golden Wheat Chain',       'category'=>'necklaces','price'=>390, 'icon'=>'✦', 'rating'=>4, 'badge'=>'', 'material'=>'18k Yellow Gold'],

    // ─── EARRINGS ──────────────────────────────────────────
    ['id'=>15, 'name'=>'Crescent Moon Drops',      'category'=>'earrings', 'price'=>245, 'icon'=>'☽', 'rating'=>5, 'badge'=>'Top Rated', 'material'=>'14k Gold'],
    ['id'=>16, 'name'=>'Halo Stud Diamonds',       'category'=>'earrings', 'price'=>1450,'icon'=>'◈', 'rating'=>5, 'badge'=>'Luxury', 'material'=>'Diamond & Platinum'],
    ['id'=>17, 'name'=>'Cascade Chandelier',       'category'=>'earrings', 'price'=>580, 'icon'=>'✦', 'rating'=>5, 'badge'=>'New', 'material'=>'18k Rose Gold'],
    ['id'=>18, 'name'=>'Pearl Drop Studs',         'category'=>'earrings', 'price'=>195, 'icon'=>'◯', 'rating'=>4, 'badge'=>'Bestseller', 'material'=>'Freshwater Pearls & Silver'],
    ['id'=>19, 'name'=>'Twisted Hoops',            'category'=>'earrings', 'price'=>320, 'icon'=>'○', 'rating'=>4, 'badge'=>'', 'material'=>'18k Gold'],
    ['id'=>20, 'name'=>'Sapphire Dangle Pair',     'category'=>'earrings', 'price'=>760, 'icon'=>'◇', 'rating'=>5, 'badge'=>'Exclusive', 'material'=>'Ceylon Sapphire & Gold'],
    ['id'=>21, 'name'=>'Feather Light Drops',      'category'=>'earrings', 'price'=>210, 'icon'=>'⬟', 'rating'=>4, 'badge'=>'', 'material'=>'Sterling Silver'],
    ['id'=>22, 'name'=>'Baroque Clip-Ons',         'category'=>'earrings', 'price'=>285, 'icon'=>'◈', 'rating'=>4, 'badge'=>'', 'material'=>'Gold Plated Brass'],
    ['id'=>23, 'name'=>'Emerald Teardrop Pair',    'category'=>'earrings', 'price'=>890, 'icon'=>'▽', 'rating'=>5, 'badge'=>'Luxury', 'material'=>'Colombian Emerald'],
    ['id'=>24, 'name'=>'Citrine Sun Studs',        'category'=>'earrings', 'price'=>340, 'icon'=>'◉', 'rating'=>4, 'badge'=>'New', 'material'=>'Citrine & 14k Gold'],
    ['id'=>25, 'name'=>'Infinity Loop Hoops',      'category'=>'earrings', 'price'=>275, 'icon'=>'∞', 'rating'=>4, 'badge'=>'', 'material'=>'White Gold'],
    ['id'=>26, 'name'=>'Amethyst Cluster Drops',   'category'=>'earrings', 'price'=>420, 'icon'=>'✧', 'rating'=>5, 'badge'=>'Top Rated', 'material'=>'Amethyst & Rose Gold'],
    ['id'=>27, 'name'=>'Moonstone Studs',          'category'=>'earrings', 'price'=>315, 'icon'=>'◯', 'rating'=>4, 'badge'=>'', 'material'=>'Moonstone & Silver'],
    ['id'=>28, 'name'=>'Diamond Bar Earrings',     'category'=>'earrings', 'price'=>1100,'icon'=>'━', 'rating'=>5, 'badge'=>'Bestseller', 'material'=>'Diamond Pavé & Gold'],

    // ─── RINGS ─────────────────────────────────────────────
    ['id'=>29, 'name'=>'Solitaire Brilliance',     'category'=>'rings',    'price'=>2400,'icon'=>'◆', 'rating'=>5, 'badge'=>'Bestseller', 'material'=>'1ct Diamond & Platinum'],
    ['id'=>30, 'name'=>'Éternity Band',            'category'=>'rings',    'price'=>1850,'icon'=>'○', 'rating'=>5, 'badge'=>'Top Rated', 'material'=>'Diamond Pavé Band'],
    ['id'=>31, 'name'=>'Rose Gold Twist',          'category'=>'rings',    'price'=>640, 'icon'=>'✦', 'rating'=>4, 'badge'=>'', 'material'=>'18k Rose Gold'],
    ['id'=>32, 'name'=>'Vintage Sapphire Halo',    'category'=>'rings',    'price'=>3200,'icon'=>'◈', 'rating'=>5, 'badge'=>'Exclusive', 'material'=>'Sapphire & Diamond'],
    ['id'=>33, 'name'=>'Stackable Pearl Ring',     'category'=>'rings',    'price'=>185, 'icon'=>'◯', 'rating'=>4, 'badge'=>'New', 'material'=>'Pearl & Gold Vermeil'],
    ['id'=>34, 'name'=>'Marquise Cut Emerald',     'category'=>'rings',    'price'=>2750,'icon'=>'◇', 'rating'=>5, 'badge'=>'Luxury', 'material'=>'Emerald & 18k Gold'],
    ['id'=>35, 'name'=>'Signet Initial Ring',      'category'=>'rings',    'price'=>480, 'icon'=>'▭', 'rating'=>4, 'badge'=>'', 'material'=>'14k Yellow Gold'],
    ['id'=>36, 'name'=>'Celestial Stacker Set',    'category'=>'rings',    'price'=>595, 'icon'=>'✧', 'rating'=>5, 'badge'=>'Top Rated', 'material'=>'Mixed Metals Set of 5'],
    ['id'=>37, 'name'=>'Ruby Oval Cocktail',       'category'=>'rings',    'price'=>1680,'icon'=>'◉', 'rating'=>5, 'badge'=>'Bestseller', 'material'=>'Burmese Ruby & Gold'],
    ['id'=>38, 'name'=>'Twisted Diamond Band',     'category'=>'rings',    'price'=>1250,'icon'=>'◆', 'rating'=>5, 'badge'=>'', 'material'=>'Diamond & White Gold'],
    ['id'=>39, 'name'=>'Moonstone Cameo Ring',     'category'=>'rings',    'price'=>390, 'icon'=>'◯', 'rating'=>4, 'badge'=>'New', 'material'=>'Moonstone & Sterling Silver'],
    ['id'=>40, 'name'=>'Aquamarine Cluster',       'category'=>'rings',    'price'=>920, 'icon'=>'◈', 'rating'=>4, 'badge'=>'', 'material'=>'Aquamarine & Rose Gold'],
    ['id'=>41, 'name'=>'Bezel Diamond Solitaire',  'category'=>'rings',    'price'=>1950,'icon'=>'◆', 'rating'=>5, 'badge'=>'Top Rated', 'material'=>'Diamond & Platinum'],
    ['id'=>42, 'name'=>'Opal Dreams Ring',         'category'=>'rings',    'price'=>540, 'icon'=>'◈', 'rating'=>4, 'badge'=>'', 'material'=>'Australian Opal & Gold'],

    // ─── BRACELETS ─────────────────────────────────────────
    ['id'=>43, 'name'=>'Tennis Diamond Bracelet',  'category'=>'bracelets','price'=>3800,'icon'=>'━', 'rating'=>5, 'badge'=>'Luxury', 'material'=>'3ct Diamond Total'],
    ['id'=>44, 'name'=>'Charm Bangle Collection',  'category'=>'bracelets','price'=>420, 'icon'=>'○', 'rating'=>5, 'badge'=>'Bestseller', 'material'=>'18k Gold & Charms'],
    ['id'=>45, 'name'=>'Pearl Slider Bracelet',    'category'=>'bracelets','price'=>310, 'icon'=>'◯', 'rating'=>4, 'badge'=>'', 'material'=>'Pearls & Sterling Silver'],
    ['id'=>46, 'name'=>'Cuff Arabesque',           'category'=>'bracelets','price'=>740, 'icon'=>'⬡', 'rating'=>5, 'badge'=>'Exclusive', 'material'=>'18k Gold'],
    ['id'=>47, 'name'=>'Emerald Link Bracelet',    'category'=>'bracelets','price'=>1560,'icon'=>'◈', 'rating'=>5, 'badge'=>'New', 'material'=>'Emerald & White Gold'],
    ['id'=>48, 'name'=>'Woven Gold Bracelet',      'category'=>'bracelets','price'=>580, 'icon'=>'⬟', 'rating'=>4, 'badge'=>'', 'material'=>'14k Yellow Gold Mesh'],
    ['id'=>49, 'name'=>'Sapphire Tennis Bracelet', 'category'=>'bracelets','price'=>2400,'icon'=>'━', 'rating'=>5, 'badge'=>'Top Rated', 'material'=>'Blue Sapphire & Gold'],
    ['id'=>50, 'name'=>'Delicate Chain Bracelet',  'category'=>'bracelets','price'=>195, 'icon'=>'✦', 'rating'=>4, 'badge'=>'New', 'material'=>'14k Gold Vermeil'],
    ['id'=>51, 'name'=>'Ruby Bangle Pair',         'category'=>'bracelets','price'=>1200,'icon'=>'○', 'rating'=>5, 'badge'=>'Bestseller', 'material'=>'Ruby & 18k Gold'],
    ['id'=>52, 'name'=>'Minimalist Cuff',          'category'=>'bracelets','price'=>285, 'icon'=>'▭', 'rating'=>4, 'badge'=>'', 'material'=>'Sterling Silver'],
    ['id'=>53, 'name'=>'Amethyst Station Bracelet','category'=>'bracelets','price'=>490, 'icon'=>'◉', 'rating'=>4, 'badge'=>'', 'material'=>'Amethyst & Rose Gold'],
    ['id'=>54, 'name'=>'Multi-Strand Pearl Cuff',  'category'=>'bracelets','price'=>680, 'icon'=>'◯', 'rating'=>5, 'badge'=>'Top Rated', 'material'=>'South Sea Pearls'],
    ['id'=>55, 'name'=>'Diamond Rivière Bangle',   'category'=>'bracelets','price'=>4200,'icon'=>'◆', 'rating'=>5, 'badge'=>'Luxury', 'material'=>'Full Diamond Bangle'],
    ['id'=>56, 'name'=>'Opal Charm Bracelet',      'category'=>'bracelets','price'=>375, 'icon'=>'◈', 'rating'=>4, 'badge'=>'', 'material'=>'Opal & Sterling Silver'],

    // ─── SETS ──────────────────────────────────────────────
    ['id'=>57, 'name'=>'Lumière Parure Set',       'category'=>'sets',     'price'=>2200,'icon'=>'✦', 'rating'=>5, 'badge'=>'Top Rated', 'material'=>'Diamond & 18k Gold'],
    ['id'=>58, 'name'=>'Pearl Trio Set',           'category'=>'sets',     'price'=>890, 'icon'=>'◯', 'rating'=>5, 'badge'=>'Bestseller', 'material'=>'Baroque Pearls & Gold'],
    ['id'=>59, 'name'=>'Sapphire Suite',           'category'=>'sets',     'price'=>5600,'icon'=>'◇', 'rating'=>5, 'badge'=>'Luxury', 'material'=>'Sapphire & Platinum'],
    ['id'=>60, 'name'=>'Rose Garden Set',          'category'=>'sets',     'price'=>1100,'icon'=>'♡', 'rating'=>5, 'badge'=>'New', 'material'=>'Rose Gold Set of 3'],
    ['id'=>61, 'name'=>'Minimalist Gold Duo',      'category'=>'sets',     'price'=>640, 'icon'=>'━', 'rating'=>4, 'badge'=>'', 'material'=>'14k Gold Necklace & Earrings'],
    ['id'=>62, 'name'=>'Emerald Royale Suite',     'category'=>'sets',     'price'=>7800,'icon'=>'◈', 'rating'=>5, 'badge'=>'Exclusive', 'material'=>'Colombian Emerald'],
    ['id'=>63, 'name'=>'Moonstone Matching Set',   'category'=>'sets',     'price'=>730, 'icon'=>'◯', 'rating'=>4, 'badge'=>'', 'material'=>'Moonstone & Silver'],
    ['id'=>64, 'name'=>'Celestial Demi-Parure',    'category'=>'sets',     'price'=>1680,'icon'=>'✧', 'rating'=>5, 'badge'=>'Top Rated', 'material'=>'Diamond Stars & Gold'],

    // ─── ANKLETS & MORE ────────────────────────────────────
    ['id'=>65, 'name'=>'Gold Anklet Delicate',     'category'=>'anklets',  'price'=>195, 'icon'=>'✦', 'rating'=>4, 'badge'=>'New', 'material'=>'14k Gold Vermeil'],
    ['id'=>66, 'name'=>'Diamond Ankle Bracelet',   'category'=>'anklets',  'price'=>890, 'icon'=>'◆', 'rating'=>5, 'badge'=>'Luxury', 'material'=>'Diamond & White Gold'],
    ['id'=>67, 'name'=>'Charm Anklet',             'category'=>'anklets',  'price'=>165, 'icon'=>'○', 'rating'=>4, 'badge'=>'', 'material'=>'Sterling Silver'],
    ['id'=>68, 'name'=>'Beaded Ankle Chain',       'category'=>'anklets',  'price'=>140, 'icon'=>'◉', 'rating'=>4, 'badge'=>'Bestseller', 'material'=>'Gold-Filled Beads'],

    // ─── BROOCHES ──────────────────────────────────────────
    ['id'=>69, 'name'=>'Butterfly Diamond Brooch', 'category'=>'brooches', 'price'=>1450,'icon'=>'◈', 'rating'=>5, 'badge'=>'Exclusive', 'material'=>'Diamond & 18k Gold'],
    ['id'=>70, 'name'=>'Art Nouveau Brooch',       'category'=>'brooches', 'price'=>680, 'icon'=>'⬡', 'rating'=>5, 'badge'=>'Top Rated', 'material'=>'Enamel & Gold'],
    ['id'=>71, 'name'=>'Star Sapphire Pin',        'category'=>'brooches', 'price'=>920, 'icon'=>'✧', 'rating'=>4, 'badge'=>'', 'material'=>'Star Sapphire & Silver'],
    ['id'=>72, 'name'=>'Floral Pearl Brooch',      'category'=>'brooches', 'price'=>380, 'icon'=>'◯', 'rating'=>4, 'badge'=>'New', 'material'=>'Pearls & Rose Gold'],

    // ─── PENDANTS ──────────────────────────────────────────
    ['id'=>73, 'name'=>'Compass Rose Pendant',     'category'=>'pendants', 'price'=>320, 'icon'=>'✦', 'rating'=>4, 'badge'=>'', 'material'=>'Sterling Silver'],
    ['id'=>74, 'name'=>'Evil Eye Diamond',         'category'=>'pendants', 'price'=>780, 'icon'=>'◉', 'rating'=>5, 'badge'=>'Bestseller', 'material'=>'Diamond & 14k Gold'],
    ['id'=>75, 'name'=>'Hamsa Hand Gold',          'category'=>'pendants', 'price'=>445, 'icon'=>'◈', 'rating'=>4, 'badge'=>'', 'material'=>'18k Gold'],
    ['id'=>76, 'name'=>'Zodiac Pendant — Leo',     'category'=>'pendants', 'price'=>275, 'icon'=>'✧', 'rating'=>4, 'badge'=>'New', 'material'=>'Gold Vermeil'],
    ['id'=>77, 'name'=>'Zodiac Pendant — Virgo',   'category'=>'pendants', 'price'=>275, 'icon'=>'✧', 'rating'=>4, 'badge'=>'', 'material'=>'Gold Vermeil'],
    ['id'=>78, 'name'=>'Cross Diamond Pendant',    'category'=>'pendants', 'price'=>960, 'icon'=>'✚', 'rating'=>5, 'badge'=>'Top Rated', 'material'=>'Diamond & White Gold'],
    ['id'=>79, 'name'=>'Infinity Diamond',         'category'=>'pendants', 'price'=>680, 'icon'=>'∞', 'rating'=>5, 'badge'=>'Bestseller', 'material'=>'Diamond Pavé & Gold'],
    ['id'=>80, 'name'=>'Tree of Life Pendant',     'category'=>'pendants', 'price'=>215, 'icon'=>'⬡', 'rating'=>4, 'badge'=>'', 'material'=>'Sterling Silver'],
    ['id'=>81, 'name'=>'Locket Engraved Rose',     'category'=>'pendants', 'price'=>390, 'icon'=>'◯', 'rating'=>4, 'badge'=>'', 'material'=>'18k Rose Gold'],
    ['id'=>82, 'name'=>'Key to My Heart',          'category'=>'pendants', 'price'=>285, 'icon'=>'♡', 'rating'=>5, 'badge'=>'New', 'material'=>'14k Gold'],

    // ─── WATCHES & ACCESSORIES ─────────────────────────────
    ['id'=>83, 'name'=>'Diamond Pavé Watch',       'category'=>'watches',  'price'=>4800,'icon'=>'◆', 'rating'=>5, 'badge'=>'Luxury', 'material'=>'Diamond Bezel & Gold'],
    ['id'=>84, 'name'=>'Pearl-Link Watch',         'category'=>'watches',  'price'=>1650,'icon'=>'◯', 'rating'=>5, 'badge'=>'Top Rated', 'material'=>'Mother of Pearl & Steel'],
    ['id'=>85, 'name'=>'Rose Gold Dress Watch',    'category'=>'watches',  'price'=>2200,'icon'=>'○', 'rating'=>5, 'badge'=>'Exclusive', 'material'=>'18k Rose Gold'],
    ['id'=>86, 'name'=>'Sapphire Face Watch',      'category'=>'watches',  'price'=>3400,'icon'=>'◇', 'rating'=>5, 'badge'=>'New', 'material'=>'Sapphire & Platinum'],

    // ─── HAIR ACCESSORIES ──────────────────────────────────
    ['id'=>87, 'name'=>'Pearl Hair Pin Set',       'category'=>'hair',     'price'=>145, 'icon'=>'◯', 'rating'=>4, 'badge'=>'Bestseller', 'material'=>'Pearls & Gold Pins'],
    ['id'=>88, 'name'=>'Diamond Tiara',            'category'=>'hair',     'price'=>6500,'icon'=>'◆', 'rating'=>5, 'badge'=>'Luxury', 'material'=>'Diamond & Platinum'],
    ['id'=>89, 'name'=>'Gold Leaf Hair Comb',      'category'=>'hair',     'price'=>285, 'icon'=>'⬡', 'rating'=>4, 'badge'=>'New', 'material'=>'18k Gold'],
    ['id'=>90, 'name'=>'Bridal Headband',          'category'=>'hair',     'price'=>460, 'icon'=>'○', 'rating'=>5, 'badge'=>'Top Rated', 'material'=>'Crystal & Gold Wire'],

    // ─── ADDITIONAL RINGS & NECKLACES ──────────────────────
    ['id'=>91, 'name'=>'Pear Drop Diamond Ring',   'category'=>'rings',    'price'=>2100,'icon'=>'▽', 'rating'=>5, 'badge'=>'New', 'material'=>'Pear Diamond & Gold'],
    ['id'=>92, 'name'=>'Tanzanite Oval Ring',      'category'=>'rings',    'price'=>1480,'icon'=>'◈', 'rating'=>4, 'badge'=>'', 'material'=>'Tanzanite & White Gold'],
    ['id'=>93, 'name'=>'Gold Serpent Ring',        'category'=>'rings',    'price'=>720, 'icon'=>'◉', 'rating'=>5, 'badge'=>'Exclusive', 'material'=>'18k Gold Snake Ring'],
    ['id'=>94, 'name'=>'Amethyst Cocktail Ring',   'category'=>'rings',    'price'=>860, 'icon'=>'◉', 'rating'=>4, 'badge'=>'', 'material'=>'Amethyst & Silver'],
    ['id'=>95, 'name'=>'Diamond Cluster Ring',     'category'=>'rings',    'price'=>1750,'icon'=>'◆', 'rating'=>5, 'badge'=>'Bestseller', 'material'=>'Diamond Cluster & Gold'],
    ['id'=>96, 'name'=>'Lapis Lazuli Pendant',     'category'=>'pendants', 'price'=>340, 'icon'=>'◈', 'rating'=>4, 'badge'=>'', 'material'=>'Lapis Lazuli & Gold'],
    ['id'=>97, 'name'=>'Layered Sun Necklace',     'category'=>'necklaces','price'=>265, 'icon'=>'○', 'rating'=>4, 'badge'=>'New', 'material'=>'14k Gold Vermeil'],
    ['id'=>98, 'name'=>'Diamond V-Necklace',       'category'=>'necklaces','price'=>1890,'icon'=>'▽', 'rating'=>5, 'badge'=>'Luxury', 'material'=>'Diamond & White Gold'],
    ['id'=>99, 'name'=>'Garnet Oval Earrings',     'category'=>'earrings', 'price'=>410, 'icon'=>'◉', 'rating'=>4, 'badge'=>'', 'material'=>'Garnet & Rose Gold'],
    ['id'=>100,'name'=>'Peridot Drop Earrings',    'category'=>'earrings', 'price'=>380, 'icon'=>'▽', 'rating'=>4, 'badge'=>'New', 'material'=>'Peridot & 14k Gold'],
    ['id'=>101,'name'=>'Topaz Tennis Necklace',    'category'=>'necklaces','price'=>1350,'icon'=>'━', 'rating'=>5, 'badge'=>'Top Rated', 'material'=>'Blue Topaz & Gold'],
    ['id'=>102,'name'=>'Citrine Bangle',           'category'=>'bracelets','price'=>490, 'icon'=>'○', 'rating'=>4, 'badge'=>'', 'material'=>'Citrine & 18k Gold'],
    ['id'=>103,'name'=>'Kunzite Pendant',          'category'=>'pendants', 'price'=>620, 'icon'=>'◈', 'rating'=>4, 'badge'=>'Exclusive', 'material'=>'Kunzite & Rose Gold'],
    ['id'=>104,'name'=>'Alexandrite Ring',         'category'=>'rings',    'price'=>3200,'icon'=>'◆', 'rating'=>5, 'badge'=>'Rare', 'material'=>'Alexandrite & Platinum'],
    ['id'=>105,'name'=>'Coral Cameo Brooch',       'category'=>'brooches', 'price'=>480, 'icon'=>'◯', 'rating'=>4, 'badge'=>'', 'material'=>'Coral & Gold'],
  ];
}

function getTopRated($limit = 6) {
  $products = getProducts();
  usort($products, fn($a, $b) => $b['rating'] <=> $a['rating']);
  return array_slice($products, 0, $limit);
}

function getCategoryCounts() {
  $counts = ['all' => 0];
  foreach (getProducts() as $p) {
    $counts['all']++;
    $counts[$p['category']] = ($counts[$p['category']] ?? 0) + 1;
  }
  return $counts;
}

// ── Merge custom (DB) products with base products ─────────────
function getAllProducts(): array {
  $base   = getProducts();
  $custom = recuperer_produits_personnalises();   // from db.php
  return array_merge($base, $custom);
}

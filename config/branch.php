<?php

// system/ builds no branch UI/feature of its own -- this is just the one
// branch id every row it still writes (Sale, Delivery, Receipt, Quotation,
// RequestForm, Invoice) gets stamped with, so those rows stay properly
// branch-tagged in shared_db now that admin/ scopes reads by branch. Same
// physical row (branches.id=1, "Lilongwe Branch") admin/'s own
// config('accounting.template_branch_id') defaults to.
return [
    'default_id' => (int) env('DEFAULT_BRANCH_ID', 1),

    // Zone belongs to Country, not Branch -- same idea, one row (Malawi).
    'default_country_id' => (int) env('DEFAULT_COUNTRY_ID', 1),
];

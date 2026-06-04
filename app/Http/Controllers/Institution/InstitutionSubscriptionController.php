<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Institution\Concerns\HandlesInstitutionResources;
use App\Models\InstitutionSubscription;

class InstitutionSubscriptionController extends Controller
{
    use HandlesInstitutionResources;

    public function __construct()
    {
        $this->modelClass = InstitutionSubscription::class;
        $this->routeBase = 'subscriptions';
        $this->title = 'Subscription';
        $this->relationships = ['subscriptionPlan'];
        $this->fields = [
            'subscription_plan_id' => ['label' => 'Plan'],
            'starts_at' => ['label' => 'Starts At'],
            'ends_at' => ['label' => 'Ends At'],
            'billing_cycle' => ['label' => 'Billing Cycle'],
            'amount' => ['label' => 'Amount'],
            'status' => ['label' => 'Status'],
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Affiliate;
use Illuminate\Http\Request;

class AffiliateController extends Controller
{
    private $affiliateModel;

    public function __construct(Affiliate $affiliateModel)
    {
        $this->affiliateModel = $affiliateModel;
    }

    public function index()
    {
        $affiliates = $this->affiliateModel->getAffiliatesWhoWillBeInvited();

        return view('welcome', ['affiliates' => $affiliates]);
    }
}

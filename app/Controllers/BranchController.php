<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Branch;
use App\Models\Court;

class BranchController extends Controller
{
    public function index()
    {
        $branchModel = new Branch();

        $city   = $_GET['city'] ?? '';
        $search = $_GET['search'] ?? '';

        if (!empty($search)) {
            $branches = $branchModel->search($search);
        } elseif (!empty($city)) {
            $branches = $branchModel->findByCity($city);
        } else {
            $branches = $branchModel->findActive();
        }

        $cities = $branchModel->getCities();

        $this->render('branches/index', [
            'title'    => 'Danh Sách Chi Nhánh - SmashZone',
            'branches' => $branches,
            'cities'   => $cities,
            'city'     => $city,
            'search'   => $search,
        ]);
    }

    public function show($id)
    {
        $branchModel = new Branch();
        $courtModel  = new Court();

        $branch = $branchModel->findById($id);
        if (!$branch) {
            http_response_code(404);
            $this->render('errors/404', ['title' => 'Không tìm thấy chi nhánh']);
            return;
        }

        $courts = $courtModel->findByBranch($id);

        $this->render('branches/show', [
            'title'   => $branch['name'] . ' - SmashZone',
            'branch'  => $branch,
            'courts'  => $courts,
        ]);
    }
}

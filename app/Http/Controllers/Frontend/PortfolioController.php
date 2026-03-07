<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AboutMe;
use App\Models\Tool;
use App\Models\Technology;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Certificate; // ត្រូវប្រាកដថាមាន Model នេះ
use App\Models\Social;
use Carbon\Carbon;

class PortfolioController extends Controller
{
    public function index()
    {
        $about = AboutMe::where('status', 1)->first();
        
        // ទាញយក Technologies សម្រាប់ធ្វើ Slider
        $technologies = Technology::where('status', 1)->get();
        
        // ទាញយក Tools សម្រាប់បង្ហាញជា Grid
        $tools = Tool::where('status', 1)->get();
        
        $projects = Project::where('status', 1)->orderBy('id', 'desc')->get();
        
        // ទាញយក Experiences ដោយតម្រៀបតាមថ្ងៃចាប់ផ្តើម
        $experiences = Experience::where('status', 1)->orderBy('start_day', 'desc')->get();
        
        // ទាញយក Certificates
        $certificates = Certificate::where('status', 1)->orderBy('id', 'desc')->get();
        
        $socials = Social::where('status', 1)->get();

        return view('frontend.portfolio.index', compact(
            'about', 
            'technologies',
            'tools', 
            'projects', 
            'experiences', 
            'certificates',
            'socials'
        ));
    }
}
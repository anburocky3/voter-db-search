<?php

namespace App\Livewire;

use App\Models\VoterData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;


class VoterSearch extends Component
{
    use WithPagination;

    // Search and filter properties
    public $search = '';
    public $searchField = 'all';
    public $acNo = '';
    public $partNo = '';
    public $wardNo = '';
    public $sex = '';
    public $ageFrom = '';
    public $ageTo = '';
    public $statusType = '';
    public $relationshipType = '';
    public $perPage = 25;

    // UI state
    public $showFilters = false;
    public $sortField = 'SLNOINPART';
    public $sortDirection = 'asc';

    // Performance optimizations
    public $totalRecords = 0;
    public $searchApplied = false;

    public $availableACs = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'searchField' => ['except' => 'all'],
        'acNo' => ['except' => ''],
        'partNo' => ['except' => ''],
        'wardNo' => ['except' => ''],
        'sex' => ['except' => ''],
        'ageFrom' => ['except' => ''],
        'ageTo' => ['except' => ''],
        'statusType' => ['except' => ''],
        'relationshipType' => ['except' => ''],
        'perPage' => ['except' => 25],
        'page' => ['except' => 1],
    ];

    protected $listeners = ['refreshSearch' => '$refresh'];

    public function mount()
    {
        $this->resetPage();
        $this->calculateTotalRecords();
        $this->availableACs = VoterData::select('AC_NO')->distinct()->orderBy('AC_NO')->pluck('AC_NO')->toArray();
    }

    public function updatingSearch()
    {
        $this->resetPage();
        $this->searchApplied = !empty($this->search);
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatedSearchField()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset([
            'search', 'searchField', 'acNo', 'partNo', 'wardNo',
            'sex', 'ageFrom', 'ageTo', 'statusType', 'relationshipType'
        ]);
        $this->resetPage();
        $this->searchApplied = false;
        $this->calculateTotalRecords();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
        $this->resetPage();
    }

    public function getVotersProperty()
    {
        // Only search if criteria are applied
        if (!$this->hasSearchCriteria()) {
            return new LengthAwarePaginator([], 0, $this->perPage, $this->page ?? 1);

        }

        return $this->buildOptimizedQuery()
            ->select([
                'id', 'SLNOINPART', 'FM_NAME_EN', 'LASTNAME_EN',
                'HOUSE_NO_EN', 'AC_NO', 'PART_NO', 'WARD_NO',
                'SEX', 'AGE', 'IDCARD_NO', 'UIDNO', 'MOBILENO',
                'STATUSTYPE', 'RLN_TYPE', 'RLN_FM_NM_EN', 'address',
            ])
            ->paginate($this->perPage);
    }

    private function hasSearchCriteria(): bool
    {
        return !empty($this->search) || !empty($this->acNo) || !empty($this->partNo) ||
            !empty($this->wardNo) || !empty($this->sex) || !empty($this->ageFrom) ||
            !empty($this->ageTo) || !empty($this->statusType) || !empty($this->relationshipType);
    }

    private function buildOptimizedQuery(): Builder
    {
        $query = VoterData::query();

        // Apply most selective filters first for better performance

        // Numeric filters (usually most selective)
        if (!empty($this->acNo)) {
            $query->where('AC_NO', $this->acNo);
        }

        if (!empty($this->partNo)) {
            $query->where('PART_NO', $this->partNo);
        }

        if (!empty($this->wardNo)) {
            $query->where('WARD_NO', $this->wardNo);
        }

        // Enum-like filters
        if (!empty($this->sex)) {
            $query->where('SEX', $this->sex);
        }

        if (!empty($this->statusType)) {
            $query->where('STATUSTYPE', $this->statusType);
        }

        if (!empty($this->relationshipType)) {
            $query->where('RLN_TYPE', $this->relationshipType);
        }

        // Range filters
        if (!empty($this->ageFrom)) {
            $query->where('AGE', '>=', $this->ageFrom);
        }

        if (!empty($this->ageTo)) {
            $query->where('AGE', '<=', $this->ageTo);
        }

        // Text search last (least selective)
        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';

            if ($this->searchField === 'all') {
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('FM_NAME_EN', 'LIKE', $searchTerm)
                        ->orWhere('LASTNAME_EN', 'LIKE', $searchTerm)
                        ->orWhere('IDCARD_NO', 'LIKE', $searchTerm)
                        ->orWhere('UIDNO', 'LIKE', $searchTerm)
                        ->orWhere('MOBILENO', 'LIKE', $searchTerm)
                        ->orWhere('HOUSE_NO_EN', 'LIKE', $searchTerm);
                });
            } else {
                $query->where($this->searchField, 'LIKE', $searchTerm);
            }
        }

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        return $query;
    }

    private function calculateTotalRecords()
    {
        // Cache total records for better performance
        $this->totalRecords = Cache::remember('voter_data_total_count', 3600, function () {
            return VoterData::count();
        });
    }

    public function render()
    {
        return view('livewire.voter-search', [
            'voters' => $this->voters,
            'hasSearchCriteria' => $this->hasSearchCriteria(),
        ]);
    }
}

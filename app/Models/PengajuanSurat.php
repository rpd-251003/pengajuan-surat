<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanSurat extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'tahun_angkatan',
        'prodi_id',
        'fakultas_id',
        'jenis_surat_id',
        'keterangan',
        'approved_by_dosen_pa',
        'approved_at_dosen_pa',
        'approved_by_kaprodi',
        'approved_at_kaprodi',
        'approved_by_wadek1',
        'approved_at_wadek1',
        'approved_by_staff_tu',
        'approved_at_staff_tu',
        'approved_by_bak',
        'approved_at_bak',
        'current_approval_flow',
        'current_step',
        'approval_history',
        'status'
    ];

    protected $casts = [
        'current_approval_flow' => 'array',
        'approval_history' => 'array'
    ];

    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    public function dosenPA()
    {
        return $this->belongsTo(User::class, 'approved_by_dosen_pa');
    }

    public function kaprodi()
    {
        return $this->belongsTo(User::class, 'approved_by_kaprodi');
    }

    public function wadek1()
    {
        return $this->belongsTo(User::class, 'approved_by_wadek1');
    }

    public function staffTU()
    {
        return $this->belongsTo(User::class, 'approved_by_staff_tu');
    }

    public function staffBAK()
    {
        return $this->belongsTo(User::class, 'approved_by_bak');
    }

    public function fileApproval()
    {
        return $this->hasOne(FileApproval::class, 'id_pengajuan');
    }

    public function details()
    {
        return $this->hasMany(PengajuanDetail::class);
    }

    /**
     * Get details as associative array
     */
    public function getDetailsArray()
    {
        $details = [];
        foreach ($this->details as $detail) {
            $value = $detail->field_value;

            // Try to decode JSON if it's an array field
            if ($detail->field_type === 'array') {
                $decoded = json_decode($value, true);
                $details[$detail->field_name] = $decoded ?: $value;
            } else {
                $details[$detail->field_name] = $value;
            }
        }

        return $details;
    }

    /**
     * Initialize approval flow based on jenis surat
     */
    public function initializeApprovalFlow()
    {
        if ($this->jenisSurat) {
            $this->current_approval_flow = $this->jenisSurat->getApprovalFlow();
            $this->current_step = $this->current_approval_flow[0] ?? null;
            $this->approval_history = [];
            $this->save();
        }
    }

    /**
     * Get current approval flow
     */
    public function getCurrentApprovalFlow()
    {
        return $this->current_approval_flow ?? $this->jenisSurat->getApprovalFlow();
    }

    /**
     * Check if current user can approve this pengajuan
     */
    public function canBeApprovedBy($user, $role = null)
    {
        $currentFlow = $this->getCurrentApprovalFlow();
        $currentStep = $this->current_step;
        
        if (!$currentStep || !in_array($currentStep, $currentFlow)) {
            return false;
        }

        // Check if already approved by this step
        if ($this->isApprovedByStep($currentStep)) {
            return false;
        }

        // Check user role permissions
        switch ($currentStep) {
            case 'dosen_pa':
                return $user->canApproveAsDosenPA($this->tahun_angkatan, $this->prodi_id);
            case 'kaprodi':
                return $user->canApproveAsKaprodi($this->tahun_angkatan, $this->prodi_id);
            case 'wadek1':
                return $user->hasRole('wadek1');
            case 'tu':
                return $user->hasRole('tu');
            case 'bak':
                return $user->hasRole('bak');
            default:
                return false;
        }
    }

    /**
     * Check if step is already approved
     */
    public function isApprovedByStep($step)
    {
        switch ($step) {
            case 'dosen_pa':
                return !empty($this->approved_at_dosen_pa);
            case 'kaprodi':
                return !empty($this->approved_at_kaprodi);
            case 'wadek1':
                return !empty($this->approved_at_wadek1);
            case 'tu':
                return !empty($this->approved_at_staff_tu);
            case 'bak':
                return !empty($this->approved_at_bak);
            default:
                return false;
        }
    }

    /**
     * Approve by specific step
     */
    public function approveByStep($step, $userId)
    {
        $approvalField = $this->getApprovalFieldByStep($step);
        $timestampField = $this->getTimestampFieldByStep($step);

        if ($approvalField && $timestampField) {
            $this->$approvalField = $userId;
            $this->$timestampField = now();
            
            // Add to approval history
            $history = $this->approval_history ?? [];
            $history[] = [
                'step' => $step,
                'user_id' => $userId,
                'approved_at' => now(),
                'action' => 'approved'
            ];
            $this->approval_history = $history;
            
            // Move to next step
            $this->moveToNextStep();
            
            $this->save();
            return true;
        }
        
        return false;
    }

    /**
     * Reject by specific step
     */
    public function rejectByStep($step, $userId, $reason)
    {
        $this->status = 'ditolak';
        $this->keterangan = $reason;
        
        // Add to approval history
        $history = $this->approval_history ?? [];
        $history[] = [
            'step' => $step,
            'user_id' => $userId,
            'rejected_at' => now(),
            'action' => 'rejected',
            'reason' => $reason
        ];
        $this->approval_history = $history;
        
        $this->save();
        return true;
    }

    /**
     * Move to next approval step
     */
    private function moveToNextStep()
    {
        $currentFlow = $this->getCurrentApprovalFlow();
        $currentIndex = array_search($this->current_step, $currentFlow);
        
        if ($currentIndex !== false && $currentIndex < count($currentFlow) - 1) {
            $this->current_step = $currentFlow[$currentIndex + 1];
            $this->status = 'diproses';
        } else {
            // All approvals completed
            $this->current_step = null;
            $this->status = 'disetujui';
        }
    }

    /**
     * Get approval field name by step
     */
    private function getApprovalFieldByStep($step)
    {
        $fields = [
            'dosen_pa' => 'approved_by_dosen_pa',
            'kaprodi' => 'approved_by_kaprodi',
            'wadek1' => 'approved_by_wadek1',
            'tu' => 'approved_by_staff_tu',
            'bak' => 'approved_by_bak'
        ];
        
        return $fields[$step] ?? null;
    }

    /**
     * Get timestamp field name by step
     */
    private function getTimestampFieldByStep($step)
    {
        $fields = [
            'dosen_pa' => 'approved_at_dosen_pa',
            'kaprodi' => 'approved_at_kaprodi',
            'wadek1' => 'approved_at_wadek1',
            'tu' => 'approved_at_staff_tu',
            'bak' => 'approved_at_bak'
        ];
        
        return $fields[$step] ?? null;
    }

    /**
     * Get approval progress percentage
     */
    public function getApprovalProgress()
    {
        $flow = $this->getCurrentApprovalFlow();
        $totalSteps = count($flow);
        
        if ($totalSteps === 0) {
            return 100;
        }
        
        $completedSteps = 0;
        foreach ($flow as $step) {
            if ($this->isApprovedByStep($step)) {
                $completedSteps++;
            }
        }
        
        return round(($completedSteps / $totalSteps) * 100);
    }

    /**
     * Get current step display name
     */
    public function getCurrentStepDisplayName()
    {
        if (!$this->current_step) {
            return 'Selesai';
        }
        
        $stepNames = [
            'dosen_pa' => 'Menunggu Persetujuan Dosen PA',
            'kaprodi' => 'Menunggu Persetujuan Kaprodi',
            'wadek1' => 'Menunggu Persetujuan Wadek 1',
            'tu' => 'Menunggu Persetujuan TU',
            'bak' => 'Menunggu Persetujuan BAK'
        ];
        
        return $stepNames[$this->current_step] ?? 'Tidak Diketahui';
    }

    /**
     * Get formatted approval history for display
     */
    public function getApprovalHistory()
    {
        $flow = $this->getCurrentApprovalFlow();
        $history = [];
        
        foreach ($flow as $role) {
            $approverName = 'Belum ditentukan';
            $approvedAt = null;
            
            // Get approver info from database fields (legacy support)
            switch ($role) {
                case 'dosen_pa':
                    if ($this->approved_by_dosen_pa && $this->approved_at_dosen_pa) {
                        $approver = $this->dosenPA;
                        $approverName = $approver ? $approver->name : 'N/A';
                        $approvedAt = $this->approved_at_dosen_pa;
                    }
                    break;
                case 'kaprodi':
                    if ($this->approved_by_kaprodi && $this->approved_at_kaprodi) {
                        $approver = $this->kaprodi;
                        $approverName = $approver ? $approver->name : 'N/A';
                        $approvedAt = $this->approved_at_kaprodi;
                    }
                    break;
                case 'wadek1':
                    if ($this->approved_by_wadek1 && $this->approved_at_wadek1) {
                        $approver = $this->wadek1;
                        $approverName = $approver ? $approver->name : 'N/A';
                        $approvedAt = $this->approved_at_wadek1;
                    }
                    break;
                case 'tu':
                    if ($this->approved_by_staff_tu && $this->approved_at_staff_tu) {
                        $approver = $this->staffTU;
                        $approverName = $approver ? $approver->name : 'N/A';
                        $approvedAt = $this->approved_at_staff_tu;
                    }
                    break;
                case 'bak':
                    if ($this->approved_by_bak && $this->approved_at_bak) {
                        $approver = $this->staffBAK;
                        $approverName = $approver ? $approver->name : 'N/A';
                        $approvedAt = $this->approved_at_bak;
                    }
                    break;
            }
            
            $history[$role] = [
                'approver_name' => $approverName,
                'approved_at' => $approvedAt,
                'is_approved' => !is_null($approvedAt)
            ];
        }
        
        return $history;
    }
}

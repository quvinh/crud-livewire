<?php

namespace App\Http\Livewire;

use App\Models\Students;
use Illuminate\Validation\Rule;
use Livewire\Component;

class StudentsComponent extends Component
{
    public $student_edit_id, $student_id, $name, $email, $phone;

    public function updated($fields) {
        $this->validateOnly($fields, [
            'student_id' => 'required|unique:students,student_id, '.$this->student_edit_id,
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|min:10|numeric',
        ]);
    }

    public function resetInputs() {
        $this->student_edit_id = '';
        $this->student_id = '';
        $this->name = '';
        $this->email = '';
        $this->phone = '';
    }
    
    public function addStudents() {
        $this->resetInputs();
        $this->dispatchBrowserEvent('open-add-modal');
        // $this->dispatchBrowserEvent('load-data-table');
    }

    public function storeStudents() {
        $this->validate([
            'student_id' => 'required|unique:students',
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|min:10|numeric',
        ]);

        Students::create([
            'student_id' => $this->student_id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);

        session()->flash('message', 'Added student successfully');
        $this->resetInputs();
        $this->dispatchBrowserEvent('close-modal');
        // $this->dispatchBrowserEvent('load-data-table');
    }

    public function editStudents($id) {
        $student = Students::where('id', $id)->first();

        $this->student_edit_id = $student->id;
        $this->student_id = $student->student_id;
        $this->name = $student->name;
        $this->email = $student->email;
        $this->phone = $student->phone;

        $this->dispatchBrowserEvent('open-edit-modal');
        // $this->dispatchBrowserEvent('load-data-table');
    }

    public function updateStudents() {
        $this->validate([
            'student_id' => 'required|unique:students,student_id, '.$this->student_edit_id,
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|min:10|numeric',
        ]);
        // dd($this->student_edit_id);
        Students::where('id', $this->student_edit_id)->update(array_merge([
            'student_id' => $this->student_id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ]));

        session()->flash('message', 'Edited student successfully');
        $this->dispatchBrowserEvent('close-modal');
        // $this->dispatchBrowserEvent('load-data-table');
    }

    public function deleteStudents($id) {
        $student = Students::where('id', $id)->first();

        $this->student_edit_id = $student->id;
        $this->dispatchBrowserEvent('open-delete-modal');
        // $this->dispatchBrowserEvent('load-data-table');
    }

    public function deleteConfirmaion() {
        Students::where('id', $this->student_edit_id)->delete();
        session()->flash('message', 'Deleted student successfully');
        $this->dispatchBrowserEvent('close-modal');
        // $this->dispatchBrowserEvent('load-data-table');
    }

    public function render()
    {
        $students = Students::all();
        // $this->dispatchBrowserEvent('load-data-table');
        return view('livewire.students-component', compact('students'))->layout('livewire.layouts.base');
    }
}

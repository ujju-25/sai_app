<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Contact;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ContactsImport;
use App\Exports\ContactsExport;
use Illuminate\Http\Request;

class Contacts extends Component
{

    protected $layout = 'layouts.app';
    use WithFileUploads;
    use WithPagination;

    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $name, $email, $phone;
    public $contactId;
    public $isEditMode = false;
    public $excelFile;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:contacts,email',
        'phone' => 'nullable|string|max:15',
    ];

    public function sortBy($field)
    {
        $this->sortDirection = $this->sortField === $field
            ? ($this->sortDirection === 'asc' ? 'desc' : 'asc')
            : 'asc';

        $this->sortField = $field;
    }

    public function import(Request $request)
    {
        $this->validate([
            'excelFile' => 'required|mimes:xlsx',
        ]);

        Excel::import(new ContactsImport, $this->excelFile->getRealPath());

        session()->flash('message', 'Contacts imported successfully.');
    
    }

    public function import1(Request $request)
    {
        $file = $request->file('file');
        Excel::import(new ContactsImport, $file);

        return redirect()->back()->with('success', 'Contacts imported successfully!');
    }

    public function export()
    {
        return Excel::download(new ContactsExport, 'contacts.xlsx');
    }

    public function render()
    {
        $contacts = Contact::orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.contacts', ['contacts' => $contacts]);
    }

    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->contactId = '';
        $this->isEditMode = false;
    }

    public function store()
    {
        $this->validate();

        Contact::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);

        session()->flash('message', 'Contact created successfully.');

        $this->resetForm();
    }

    public function edit($id)
    {
        $contact = Contact::findOrFail($id);
        $this->contactId = $id;
        $this->name = $contact->name;
        $this->email = $contact->email;
        $this->phone = $contact->phone;
        $this->isEditMode = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:contacts,email,' . $this->contactId,
            'phone' => 'nullable|string|max:15',
        ]);

        $contact = Contact::findOrFail($this->contactId);

        $contact->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);

        session()->flash('message', 'Contact updated successfully.');

        $this->resetForm();
    }

    public function delete($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        session()->flash('message', 'Contact deleted successfully.');
    }
}

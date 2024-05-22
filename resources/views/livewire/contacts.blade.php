<div>
    <h1>Contact Management</h1>

    <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'store' }}">
        <input type="hidden" wire:model="contactId">

        <div>
            <label for="name">Name</label>
            <input type="text" id="name" wire:model="name">
            @error('name') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="email">Email</label>
            <input type="email" id="email" wire:model="email">
            @error('email') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="phone">Phone</label>
            <input type="text" id="phone" wire:model="phone">
            @error('phone') <span class="error">{{ $message }}</span> @enderror
        </div>

        <button type="submit">{{ $isEditMode ? 'Update' : 'Save' }}</button>
        @if($isEditMode)
        <button type="button" wire:click="resetForm">Cancel</button>
        @endif
    </form>

    
        <input type="file" wire:model="excelFile">
        <button wire:click="import">Import Contacts</button>
    

    <button wire:click="export">Export Contacts</button>

    @if (session()->has('message'))
    <div>{{ session('message') }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th><button wire:click="sortBy('name')">Name</button></th>
                <th><button wire:click="sortBy('email')">Email</button></th>
                <th><button wire:click="sortBy('phone')">Phone</button></th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contacts as $contact)
            <tr>
                <td>{{ $contact->name }}</td>
                <td>{{ $contact->email }}</td>
                <td>{{ $contact->phone }}</td>
                <td>
                    <button wire:click="edit({{ $contact->id }})">Edit</button>
                    <button wire:click="delete({{ $contact->id }})">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $contacts->links() }}
</div>


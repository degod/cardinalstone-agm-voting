@extends('layout.master')
@section('title', 'Agenda Edit')

@section('body_content')
<h3>Agenda Edit</h3>
<p>Updating {{ $agenda->agm->title }} agenda items</p>

<div class="row">
    <div class="col-lg-8">
        <form action="{{ route('agendas.update', $agenda->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="agm_id" class="form-label">AGM</label>
                <input type="text" class="form-control" id="agm_id" readonly value="{{ $agenda->agm->title }}">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Agenda Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"
                    required>{{ old('description', $agenda->description) }}</textarea>
            </div>
            <div class="mb-3">
                <label for="is_active" class="form-label">Status</label>
                <select class="form-select" id="is_active" name="is_active" required>
                    <option value=""> -- Select Status --</option>
                    @foreach($itemStatuses as $statusKey=>$statusValue)
                    <option value="{{ $statusKey }}" {{ $agenda->is_active == $statusKey ? 'selected' : '' }}>
                        {{ $statusValue }}</option>
                    @endforeach
                </select>
            </div>

            <hr>

            <h5>
                Agenda Items

                <button type="button" id="add-item" class="btn btn-secondary float-end">+ Add Item</button>
            </h5>
            <hr>
            <ul id="agenda-items" class="list-group mb-3">
                @foreach($agenda->items as $index => $item)
                <li class="list-group-item d-flex align-items-center" data-id="{{ $item->id }}">
                    <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                    <input type="hidden" class="item-number" name="items[{{ $index }}][item_number]"
                        value="{{ $item->item_number }}">
                    <input type="text" class="form-control me-2" name="items[{{ $index }}][title]"
                        value="{{ old('items.'.$index.'.title', $item->title) }}" required>
                    <select class="form-select me-2" name="items[{{ $index }}][item_type]">
                        @foreach($itemTypes as $key => $label)
                        <option value="{{ $key }}" {{ $item->item_type == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                    <select class="form-select me-2" name="items[{{ $index }}][voting_type]">
                        @foreach($voteTypes as $key => $label)
                        <option value="{{ $key }}" {{ $item->voting_type == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-sm btn-danger remove-item">Remove</button>
                    <span class="ms-2 text-muted" style="cursor: grab;">☰</span>
                </li>
                @endforeach
            </ul>

            <button type="submit" class="btn btn-primary">Update Agenda</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const agendaItems = document.getElementById('agenda-items');
const addBtn = document.getElementById('add-item');

let draggedItem = null;

function makeDraggable(li) {
    li.setAttribute('draggable', true);
}

function refreshItemNumbers() {
    [...agendaItems.children].forEach((li, index) => {
        li.querySelector('.item-number').value = index + 1;
    });
}

agendaItems.querySelectorAll('li').forEach(makeDraggable);

// Drag & drop reordering
agendaItems.addEventListener('dragstart', e => {
    draggedItem = e.target.closest('li');
    e.dataTransfer.effectAllowed = 'move';
});

agendaItems.addEventListener('dragover', e => {
    e.preventDefault();
    const target = e.target.closest('li');
    if (target && target !== draggedItem) {
        const rect = target.getBoundingClientRect();
        const next = (e.clientY - rect.top) / (rect.height) > 0.5;
        agendaItems.insertBefore(draggedItem, next ? target.nextSibling : target);
    }
});

agendaItems.addEventListener('drop', () => {
    draggedItem = null;
    refreshItemNumbers();
});

// Remove item
agendaItems.addEventListener('click', e => {
    if (e.target.classList.contains('remove-item')) {
        e.preventDefault();
        e.target.closest('li').remove();
        refreshItemNumbers();
    }
});

// Add item
addBtn.addEventListener('click', () => {
    const index = agendaItems.querySelectorAll('li').length;
    const li = document.createElement('li');
    li.className = "list-group-item d-flex align-items-center";
    li.innerHTML = `
        <input type="hidden" name="items[${index}][id]" value="">
        <input type="hidden" class="item-number" name="items[${index}][item_number]" value="${index + 1}">
        <input type="text" class="form-control me-2" name="items[${index}][title]" placeholder="Title" required>
        <select class="form-select me-2" name="items[${index}][item_type]">
            @foreach($itemTypes as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
            @endforeach
        </select>
        <select class="form-select me-2" name="items[${index}][voting_type]">
            @foreach($voteTypes as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
            @endforeach
        </select>
        <button type="button" class="btn btn-sm btn-danger remove-item">Remove</button>
        <span class="ms-2 text-muted" style="cursor: grab;">☰</span>
    `;
    makeDraggable(li);
    agendaItems.appendChild(li);
    refreshItemNumbers();
});
</script>

@endpush
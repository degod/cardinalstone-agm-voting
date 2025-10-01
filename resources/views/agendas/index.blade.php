@extends('layout.master')

@section('title', 'Agenda Management')

@section('body_content')
<div class="container mt-5">
    <h1 class="mb-4">Agenda Management</h1>

    <a href="#" data-bs-toggle="modal" data-bs-target="#addAgendaModal" class="btn btn-success mb-3 float-end">Add New
        Agenda</a>

    <div class="modal fade" id="addAgendaModal" tabindex="-1" aria-labelledby="addAgendaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAgendaModalLabel">Add New Agenda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('agendas.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">

                        {{-- AGM Dropdown --}}
                        <div class="mb-3">
                            <label for="agm_id" class="form-label">AGM</label>
                            <select class="form-select" id="agm_id" name="agm_id" required>
                                <option value="">-- Select AGM --</option>
                                @foreach($agms as $agm)
                                <option value="{{ $agm->id }}">{{ $agm->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        {{-- Agenda Items Section --}}
                        <div class="mb-3">
                            <h6>Agenda Items</h6>
                            <div id="agenda-items-container" class="d-flex flex-column gap-2"></div>
                            <button type="button" class="btn btn-sm btn-success mt-2" id="add-agenda-item">
                                + Add Item
                            </button>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Agenda</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>AGM</th>
                <th>Description</th>
                <th>Status</th>
                <th>Items</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($agendas->items() as $agenda)
            <tr>
                <td>
                    {{ $agenda->agm->title }}
                </td>
                <td>{{ $agenda->description }}</td>
                <td>{{ $itemStatuses[$agenda->is_active] }}</td>
                <td>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#agendaModal{{ $agenda->id }}">
                        View
                    </button>
                    <div class="modal fade" id="agendaModal{{ $agenda->id }}" tabindex="-1"
                        aria-labelledby="agendaModalLabel{{ $agenda->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="agendaModalLabel{{ $agenda->id }}">
                                        Agenda Items
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <p><b>Description:</b> {{ $agenda->description }}</p>
                                    <p><b>Status:</b> {{ $agenda->is_active ? 'Active' : 'Inactive' }}</p>

                                    <hr>
                                    <h6>Agenda Items</h6>
                                    <ul class="list-group">
                                        @php $countIndex = 0; @endphp
                                        @foreach($agenda->items as $item)
                                        <li class="list-group-item">
                                            <a href="{{ route('agendas.view', $item->id) }}">
                                                <b>{{ ++$countIndex }}. {{ $item->title }}</b><br>
                                            </a>

                                            {{ $item->description }} <br>
                                            <small>
                                                <b>Type:</b> {{ $item->item_type }} |
                                                <b>Voting:</b> {{ $item->voting_type }}
                                            </small>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <a href="{{ route('agendas.edit', $agenda->id) }}" class="btn btn-primary btn-sm">Edit</a>

                    <form action="{{ route('agendas.destroy', $agenda->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">No Agendas found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($agendas->hasPages())
    {{ $agendas->links('pagination::bootstrap-5') }}
    @endif
</div>

<template id="agenda-item-template">
    <div class="agenda-item border p-3 rounded bg-light d-flex align-items-center gap-2" draggable="true">
        <input type="hidden" name="items[__INDEX__][item_number]" class="item-number" value="__INDEX__">

        <input type="text" class="form-control" name="items[__INDEX__][title]" placeholder="Title" required>

        <select class="form-select" name="items[__INDEX__][item_type]" required>
            <option value="">-- Type --</option>
            @foreach($itemTypes as $key => $label)
            <option value="{{ $key }}">{{ $label }}</option>
            @endforeach
        </select>

        <select class="form-select" name="items[__INDEX__][voting_type]" required>
            <option value="">-- Voting Type --</option>
            @foreach($voteTypes as $key => $label)
            <option value="{{ $key }}">{{ $label }}</option>
            @endforeach
        </select>

        <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
    </div>
</template>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('agenda-items-container');
        const template = document.getElementById('agenda-item-template').innerHTML;
        const addBtn = document.getElementById('add-agenda-item');

        let itemIndex = 0;

        function refreshIndexes() {
            [...container.children].forEach((el, i) => {
                el.querySelector('.item-number').value = i + 1;
                el.querySelectorAll('input, select').forEach(input => {
                    input.name = input.name.replace(/\[\d+\]/, `[${i + 1}]`);
                });
            });
        }

        addBtn.addEventListener('click', () => {
            itemIndex++;
            const html = template.replace(/__INDEX__/g, itemIndex);
            const wrapper = document.createElement('div');
            wrapper.innerHTML = html;
            container.appendChild(wrapper.firstElementChild);
            refreshIndexes();
        });

        container.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-item')) {
                e.target.closest('.agenda-item').remove();
                refreshIndexes();
            }
        });

        // Drag-and-drop reordering
        let dragged;
        container.addEventListener('dragstart', (e) => {
            dragged = e.target;
            e.target.style.opacity = 0.5;
        });
        container.addEventListener('dragend', (e) => {
            e.target.style.opacity = '';
        });
        container.addEventListener('dragover', (e) => {
            e.preventDefault();
            const afterElement = [...container.children].find(child =>
                e.clientY < child.getBoundingClientRect().top + child.offsetHeight / 2
            );
            if (afterElement) {
                container.insertBefore(dragged, afterElement);
            } else {
                container.appendChild(dragged);
            }
        });
        container.addEventListener('drop', () => {
            refreshIndexes();
        });
    });
</script>
@endpush
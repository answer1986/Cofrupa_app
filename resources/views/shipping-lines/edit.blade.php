@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-ship"></i> Editar Naviera</h2>
            <a href="{{ route('shipping-lines.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-ship"></i> Información de la Naviera</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('shipping-lines.update', $shippingLine->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nombre de la Naviera *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $shippingLine->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="code" class="form-label">Código *</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror"
                                   id="code" name="code" value="{{ old('code', $shippingLine->code) }}" required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contact_name" class="form-label">Contacto Principal</label>
                            <input type="text" class="form-control @error('contact_name') is-invalid @enderror"
                                   id="contact_name" name="contact_name" value="{{ old('contact_name', $shippingLine->contact_name) }}">
                            @error('contact_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="contact_email" class="form-label">Email Principal</label>
                            <input type="email" class="form-control @error('contact_email') is-invalid @enderror"
                                   id="contact_email" name="contact_email" value="{{ old('contact_email', $shippingLine->contact_email) }}" autocomplete="email">
                            @error('contact_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="contact_phone" class="form-label">Teléfono Principal</label>
                        <input type="text" class="form-control @error('contact_phone') is-invalid @enderror"
                               id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $shippingLine->contact_phone) }}" 
                               pattern="^\+[0-9]{11}$" 
                               placeholder="+56912345678"
                               maxlength="12">
                        <small class="form-text text-muted">Formato: + seguido de 11 números (ejemplo: +56912345678)</small>
                        @error('contact_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Contactos -->
                    <hr class="my-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><i class="fas fa-users"></i> Contactos Adicionales</h5>
                        <button type="button" class="btn btn-sm btn-success" id="addContactBtn">
                            <i class="fas fa-plus"></i> Agregar Contacto
                        </button>
                    </div>
                    <div id="contactsContainer">
                        @php
                            $contacts = old('contacts', $shippingLine->contacts->toArray());
                            if (empty($contacts)) {
                                $contacts = [['contact_person' => '', 'phone' => '', 'email' => '', 'position' => '']];
                            }
                        @endphp
                        @foreach($contacts as $index => $contact)
                            <div class="contact-row mb-3 p-3 border rounded" data-contact-index="{{ $index }}">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Contacto {{ $index + 1 }}</h6>
                                    <button type="button" class="btn btn-sm btn-danger remove-contact" style="{{ count($contacts) <= 1 ? 'display: none;' : '' }}">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 mb-2">
                                        <label class="form-label">Persona de Contacto</label>
                                        <input type="text" class="form-control" name="contacts[{{ $index }}][contact_person]" value="{{ old("contacts.$index.contact_person", $contact['contact_person'] ?? '') }}" placeholder="Nombre completo">
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label class="form-label">Cargo/Posición</label>
                                        <input type="text" class="form-control" name="contacts[{{ $index }}][position]" value="{{ old("contacts.$index.position", $contact['position'] ?? '') }}" placeholder="Ej: Gerente">
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label class="form-label">Teléfono</label>
                                        <input type="text" class="form-control" name="contacts[{{ $index }}][phone]" value="{{ old("contacts.$index.phone", $contact['phone'] ?? '') }}" placeholder="Ej: +56 9 1234 5678">
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="contacts[{{ $index }}][email]" value="{{ old("contacts.$index.email", $contact['email'] ?? '') }}" placeholder="ejemplo@correo.com">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', $shippingLine->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Activa
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notas</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                  id="notes" name="notes" rows="3">{{ old('notes', $shippingLine->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Datos de Facturación -->
                    <hr class="my-4">
                    <h5 class="mb-3"><i class="fas fa-file-invoice-dollar"></i> Datos de Facturación</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tax_id" class="form-label">
                                <i class="fas fa-id-card"></i> RUT / Tax ID
                            </label>
                            <input type="text" class="form-control @error('tax_id') is-invalid @enderror"
                                   id="tax_id" name="tax_id" value="{{ old('tax_id', $shippingLine->tax_id) }}"
                                   placeholder="Ej: 77.706.225-5">
                            @error('tax_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Información Bancaria -->
                    <hr class="my-4">
                    <h5 class="mb-3"><i class="fas fa-university"></i> Información Bancaria</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bank_name" class="form-label">
                                <i class="fas fa-building"></i> Banco
                            </label>
                            <input type="text" class="form-control @error('bank_name') is-invalid @enderror"
                                   id="bank_name" name="bank_name" value="{{ old('bank_name', $shippingLine->bank_name) }}"
                                   placeholder="Ej: Banco Itaú">
                            @error('bank_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="bank_account_type" class="form-label">
                                <i class="fas fa-wallet"></i> Tipo de Cuenta
                            </label>
                            <select class="form-control @error('bank_account_type') is-invalid @enderror"
                                    id="bank_account_type" name="bank_account_type">
                                <option value="">Seleccione...</option>
                                <option value="corriente" {{ old('bank_account_type', $shippingLine->bank_account_type) == 'corriente' ? 'selected' : '' }}>Cuenta Corriente</option>
                                <option value="vista" {{ old('bank_account_type', $shippingLine->bank_account_type) == 'vista' ? 'selected' : '' }}>Cuenta Vista</option>
                                <option value="ahorro" {{ old('bank_account_type', $shippingLine->bank_account_type) == 'ahorro' ? 'selected' : '' }}>Cuenta de Ahorro</option>
                            </select>
                            @error('bank_account_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bank_account_number" class="form-label">
                                <i class="fas fa-hashtag"></i> Número de Cuenta
                            </label>
                            <input type="text" class="form-control @error('bank_account_number') is-invalid @enderror"
                                   id="bank_account_number" name="bank_account_number" value="{{ old('bank_account_number', $shippingLine->bank_account_number) }}"
                                   placeholder="Ej: 0228557656">
                            @error('bank_account_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('shipping-lines.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-success">Actualizar Naviera</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.getElementById('contact_phone');
    
    phoneInput.addEventListener('input', function(e) {
        let value = e.target.value;
        value = value.replace(/[^0-9+]/g, '');
        if (value.length > 0 && value[0] !== '+') {
            value = '+' + value.replace(/\+/g, '');
        }
        if (value.length > 12) {
            value = value.substring(0, 12);
        }
        e.target.value = value;
    });
    
    phoneInput.addEventListener('keypress', function(e) {
        if (e.key === '+' && this.value.includes('+')) {
            e.preventDefault();
        }
    });

    // Gestión de contactos múltiples
    const contactsContainer = document.getElementById('contactsContainer');
    const addContactBtn = document.getElementById('addContactBtn');
    let contactIndex = {{ count($contacts) }};
    const maxContacts = 5;

    function updateContactNumbers() {
        const contactRows = contactsContainer.querySelectorAll('.contact-row');
        contactRows.forEach((row, index) => {
            const title = row.querySelector('h6');
            title.textContent = `Contacto ${index + 1}`;
            
            const removeBtn = row.querySelector('.remove-contact');
            if (contactRows.length > 1) {
                removeBtn.style.display = 'block';
            } else {
                removeBtn.style.display = 'none';
            }
        });

        if (contactRows.length >= maxContacts) {
            addContactBtn.disabled = true;
            addContactBtn.classList.add('disabled');
        } else {
            addContactBtn.disabled = false;
            addContactBtn.classList.remove('disabled');
        }
    }

    addContactBtn.addEventListener('click', function() {
        const contactRows = contactsContainer.querySelectorAll('.contact-row');
        
        if (contactRows.length >= maxContacts) {
            alert('Solo se pueden agregar hasta ' + maxContacts + ' contactos');
            return;
        }

        const newContactHtml = `
            <div class="contact-row mb-3 p-3 border rounded" data-contact-index="${contactIndex}">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Contacto ${contactIndex + 1}</h6>
                    <button type="button" class="btn btn-sm btn-danger remove-contact">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Persona de Contacto</label>
                        <input type="text" class="form-control" name="contacts[${contactIndex}][contact_person]" value="" placeholder="Nombre completo">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Cargo/Posición</label>
                        <input type="text" class="form-control" name="contacts[${contactIndex}][position]" value="" placeholder="Ej: Gerente">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Teléfono</label>
                        <input type="text" class="form-control" name="contacts[${contactIndex}][phone]" value="" placeholder="Ej: +56 9 1234 5678">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="contacts[${contactIndex}][email]" value="" placeholder="ejemplo@correo.com">
                    </div>
                </div>
            </div>
        `;

        contactsContainer.insertAdjacentHTML('beforeend', newContactHtml);
        contactIndex++;
        updateContactNumbers();

        const newRow = contactsContainer.lastElementChild;
        const removeBtn = newRow.querySelector('.remove-contact');
        removeBtn.addEventListener('click', function() {
            removeContact(newRow);
        });
    });

    function removeContact(row) {
        const contactRows = contactsContainer.querySelectorAll('.contact-row');
        
        if (contactRows.length <= 1) {
            alert('Debe haber al menos un contacto');
            return;
        }

        row.remove();
        updateContactNumbers();
        reindexContacts();
    }

    function reindexContacts() {
        const contactRows = contactsContainer.querySelectorAll('.contact-row');
        contactRows.forEach((row, newIndex) => {
            const inputs = row.querySelectorAll('input');
            inputs.forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    const newName = name.replace(/contacts\[\d+\]/, `contacts[${newIndex}]`);
                    input.setAttribute('name', newName);
                }
            });
        });
        updateContactNumbers();
    }

    contactsContainer.querySelectorAll('.remove-contact').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('.contact-row');
            removeContact(row);
        });
    });

    updateContactNumbers();
});
</script>
@endsection


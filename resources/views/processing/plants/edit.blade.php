@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2><i class="fas fa-building"></i> Editar Planta</h2>
        </div>
    </div>

    <form action="{{ route('processing.plants.update', $plant->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Nombre *</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $plant->name) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="code" class="form-label">Código *</label>
                        <input type="text" class="form-control" id="code" name="code" value="{{ old('code', $plant->code) }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="address" class="form-label">Dirección</label>
                        <textarea class="form-control" id="address" name="address" rows="2">{{ old('address', $plant->address) }}</textarea>
                    </div>
                </div>
                <!-- Contactos -->
                <hr class="my-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0"><i class="fas fa-users"></i> Contactos</h5>
                    <button type="button" class="btn btn-sm btn-success" id="addContactBtn">
                        <i class="fas fa-plus"></i> Agregar Contacto
                    </button>
                </div>
                <div id="contactsContainer">
                    @php
                        $contacts = old('contacts', $plant->contacts->toArray());
                        if (empty($contacts)) {
                            $contacts = [['contact_person' => '', 'phone' => '', 'email' => '']];
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
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">Persona de Contacto</label>
                                    <input type="text" class="form-control" name="contacts[{{ $index }}][contact_person]" value="{{ old("contacts.$index.contact_person", $contact['contact_person'] ?? '') }}" placeholder="Nombre completo">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">Teléfono</label>
                                    <input type="text" class="form-control" name="contacts[{{ $index }}][phone]" value="{{ old("contacts.$index.phone", $contact['phone'] ?? '') }}" placeholder="Ej: +56 9 1234 5678">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="contacts[{{ $index }}][email]" value="{{ old("contacts.$index.email", $contact['email'] ?? '') }}" placeholder="ejemplo@correo.com">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="notes" class="form-label">Notas</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $plant->notes) }}</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $plant->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Planta Activa</label>
                        </div>
                    </div>
                </div>

                <!-- Datos de Facturación -->
                <hr class="my-4">
                <h5 class="mb-3"><i class="fas fa-file-invoice-dollar"></i> Datos de Facturación</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tax_id" class="form-label">
                            <i class="fas fa-id-card"></i> RUT / Tax ID
                        </label>
                        <input type="text" class="form-control" id="tax_id" name="tax_id" value="{{ old('tax_id', $plant->tax_id) }}"
                               placeholder="Ej: 77.706.225-5">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="payment_currency" class="form-label">
                            <i class="fas fa-money-bill-wave"></i> Se paga en
                        </label>
                        <select class="form-select" id="payment_currency" name="payment_currency">
                            <option value="">Seleccione...</option>
                            <option value="usd" {{ old('payment_currency', $plant->payment_currency) == 'usd' ? 'selected' : '' }}>Dólares (USD)</option>
                            <option value="clp" {{ old('payment_currency', $plant->payment_currency) == 'clp' ? 'selected' : '' }}>Pesos (CLP)</option>
                        </select>
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
                        <input type="text" class="form-control" id="bank_name" name="bank_name" value="{{ old('bank_name', $plant->bank_name) }}"
                               placeholder="Ej: Banco Itaú">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="bank_account_type" class="form-label">
                            <i class="fas fa-wallet"></i> Tipo de Cuenta
                        </label>
                        <select class="form-control" id="bank_account_type" name="bank_account_type">
                            <option value="">Seleccione...</option>
                            <option value="corriente" {{ old('bank_account_type', $plant->bank_account_type) == 'corriente' ? 'selected' : '' }}>Cuenta Corriente</option>
                            <option value="vista" {{ old('bank_account_type', $plant->bank_account_type) == 'vista' ? 'selected' : '' }}>Cuenta Vista</option>
                            <option value="ahorro" {{ old('bank_account_type', $plant->bank_account_type) == 'ahorro' ? 'selected' : '' }}>Cuenta de Ahorro</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="bank_account_number" class="form-label">
                            <i class="fas fa-hashtag"></i> Número de Cuenta
                        </label>
                        <input type="text" class="form-control" id="bank_account_number" name="bank_account_number" value="{{ old('bank_account_number', $plant->bank_account_number) }}"
                               placeholder="Ej: 0228557656">
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="{{ route('processing.plants.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const contactsContainer = document.getElementById('contactsContainer');
    const addContactBtn = document.getElementById('addContactBtn');
    let contactIndex = {{ count($contacts) }};
    const maxContacts = 3;

    // Función para actualizar el número de contacto y mostrar/ocultar botones de eliminar
    function updateContactNumbers() {
        const contactRows = contactsContainer.querySelectorAll('.contact-row');
        contactRows.forEach((row, index) => {
            const title = row.querySelector('h6');
            title.textContent = `Contacto ${index + 1}`;
            
            const removeBtn = row.querySelector('.remove-contact');
            // Mostrar botón eliminar solo si hay más de un contacto
            if (contactRows.length > 1) {
                removeBtn.style.display = 'block';
            } else {
                removeBtn.style.display = 'none';
            }
        });

        // Deshabilitar botón agregar si ya hay 3 contactos
        if (contactRows.length >= maxContacts) {
            addContactBtn.disabled = true;
            addContactBtn.classList.add('disabled');
        } else {
            addContactBtn.disabled = false;
            addContactBtn.classList.remove('disabled');
        }
    }

    // Agregar nuevo contacto
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
                    <div class="col-md-4 mb-2">
                        <label class="form-label">Persona de Contacto</label>
                        <input type="text" class="form-control" name="contacts[${contactIndex}][contact_person]" value="" placeholder="Nombre completo">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label">Teléfono</label>
                        <input type="text" class="form-control" name="contacts[${contactIndex}][phone]" value="" placeholder="Ej: +56 9 1234 5678">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="contacts[${contactIndex}][email]" value="" placeholder="ejemplo@correo.com">
                    </div>
                </div>
            </div>
        `;

        contactsContainer.insertAdjacentHTML('beforeend', newContactHtml);
        contactIndex++;
        updateContactNumbers();

        // Agregar evento al nuevo botón de eliminar
        const newRow = contactsContainer.lastElementChild;
        const removeBtn = newRow.querySelector('.remove-contact');
        removeBtn.addEventListener('click', function() {
            removeContact(newRow);
        });
    });

    // Eliminar contacto
    function removeContact(row) {
        const contactRows = contactsContainer.querySelectorAll('.contact-row');
        
        if (contactRows.length <= 1) {
            alert('Debe haber al menos un contacto');
            return;
        }

        row.remove();
        updateContactNumbers();
        
        // Reindexar los contactos restantes
        reindexContacts();
    }

    // Reindexar contactos después de eliminar
    function reindexContacts() {
        const contactRows = contactsContainer.querySelectorAll('.contact-row');
        contactRows.forEach((row, newIndex) => {
            // Actualizar los nombres de los campos
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

    // Agregar eventos a los botones de eliminar existentes
    contactsContainer.querySelectorAll('.remove-contact').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('.contact-row');
            removeContact(row);
        });
    });

    // Inicializar
    updateContactNumbers();
});
</script>
@endsection


<div>
    <label>Marque</label>
    <input type="text" name="brand" value="{{ old('brand', $car->brand ?? '') }}" required>
</div>

<div>
    <label>Modèle</label>
    <input type="text" name="model" value="{{ old('model', $car->model ?? '') }}" required>
</div>

<div>
    <label>Immatriculation</label>
    <input type="text" name="registration_number" value="{{ old('registration_number', $car->registration_number ?? '') }}" required>
</div>

<div>
    <label>Année</label>
    <input type="number" name="year" value="{{ old('year', $car->year ?? '') }}" required>
</div>

<div>
    <label>Prix par jour</label>
    <input type="number" name="price_per_day" value="{{ old('price_per_day', $car->price_per_day ?? '') }}" required>
</div>

<div>
    <label>Description</label>
    <textarea name="description">{{ old('description', $car->description ?? '') }}</textarea>
</div>

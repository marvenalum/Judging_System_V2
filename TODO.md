# CriteriaController Update Plan

## Task
Update CriteriaController to use the data structure: id, event_id, category_id, name, max_score, weight

## Changes Required

### 1. Add missing imports
- [ ] Add `use App\Models\Category;` import

### 2. create() method
- [ ] Add `$events = Event::all();` to pass events to view
- [ ] Pass `$events` to view

### 3. store() method
- [ ] Change `maximum_score` validation to `max_score`
- [ ] Remove `description` validation
- [ ] Remove `status` validation
- [ ] Add `event_id` validation

### 4. edit() method
- [ ] Add `$events = Event::all();` to pass events to view
- [ ] Pass `$events` to view

### 5. update() method
- [ ] Change `percentage_weight` validation to `weight`
- [ ] Remove `description` validation
- [ ] Remove `status` validation
- [ ] Add `category_id` validation

### 6. show() method
- [ ] Change `->load('category')` to `->load(['category', 'event'])`

## Implementation Status
- [ ] Not started

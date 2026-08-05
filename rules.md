# rules.md

# Plant Lifecycle

## Stages

* SEED
* GERMINATION
* SEEDLING
* VEGETATIVE
* FLOWERING
* FRUITING
* HARVEST
* FINISHED
* DEAD

---

# Stage Transition Rules

## Seed → Germination

```python
IF ageInDays >= plant.germinationDay
THEN
stage = GERMINATION
create GERMINATION event
```

## Germination → Seedling

```python
IF ageInDays >= plant.seedlingDay
THEN
stage = SEEDLING
create SEEDLING event
```

## Seedling → Vegetative

```python
IF ageInDays >= plant.vegetativeDay
THEN
stage = VEGETATIVE
create VEGETATIVE event
```

## Vegetative → Flowering

```python
IF floweringDay IS NOT NULL
AND ageInDays >= floweringDay
THEN
stage = FLOWERING
create FLOWERING event
```

## Flowering → Fruiting

```python
IF fruitingDay IS NOT NULL
AND ageInDays >= fruitingDay
THEN
stage = FRUITING
create FRUITING event
```

## Fruiting → Harvest

```python
IF ageInDays >= harvestDay
THEN
stage = HARVEST
create HARVEST_READY event
```

---

# Calendar Rules

## Planting

```python
WHEN plantCreated

create PLANTING event
```

---

## Transplant

```python
IF ageInDays == seedlingDay

create TRANSPLANT event
reset HST = 0
```

---

## Watering Reminder

```python
EVERY DAY

07:00

16:00

create WATERING_REMINDER
```

---

## Fertilizer Reminder

```python
EVERY 7 DAYS

create FERTILIZER_REMINDER
```

---

## Pest Inspection

```python
EVERY 3 DAYS

create PEST_INSPECTION
```

---

## Pruning

```python
IF plant.family == Solanaceae
AND HST >= 15

create PRUNING event
```

---

# Harvest Rules

## Harvest Ready

```python
IF HST >= harvestStartDay

create HARVEST_READY
```

---

## Late Harvest Warning

```python
IF HST > harvestEndDay

create LATE_HARVEST_WARNING
```

---

## Harvest Completed

```python
IF action == HARVEST_COMPLETED

mark event completed
```

---

## Multiple Harvest

```python
IF multipleHarvest == true

schedule next harvest
```

---

## Single Harvest

```python
IF multipleHarvest == false
AND action == HARVEST_COMPLETED

status = FINISHED
```

---

# Replant Rules

```python
IF status == FINISHED

create REPLANT event
```

---

# Dead Plant Rules

```python
IF status == DEAD

cancel all future events

create REPLANT event
```

---

# Event Status

* PENDING
* COMPLETED
* MISSED
* SKIPPED

---

# Plant Status

* ACTIVE
* PRODUCTIVE
* HARVESTING
* FINISHED
* DEAD

---

# Missed Events

## Missed Watering

```python
IF WATERING_REMINDER missed >= 2

create MISSED_WATERING_WARNING
```

## Missed Fertilizer

```python
IF FERTILIZER_REMINDER missed >= 1

create MISSED_FERTILIZER_WARNING
```

---

# Weather Rules (Optional)

## Heavy Rain

```python
IF rainProbability > 80%

skip WATERING_REMINDER

create HEAVY_RAIN_WARNING
```

## Heat Warning

```python
IF temperature > plant.maxTemperature

create HEAT_WARNING
```

---

# Seasonal Rules

```python
IF currentMonth IN plant.recommendedMonths

create RECOMMENDED_PLANTING event
```

---

# Event Priority

CRITICAL

HIGH

MEDIUM

LOW

---

# Event Types

PLANTING

GERMINATION

SEEDLING

TRANSPLANT

VEGETATIVE

FLOWERING

FRUITING

HARVEST_READY

LATE_HARVEST_WARNING

WATERING_REMINDER

FERTILIZER_REMINDER

PEST_INSPECTION

PRUNING

MISSED_WATERING_WARNING

MISSED_FERTILIZER_WARNING

HEAT_WARNING

HEAVY_RAIN_WARNING

REPLANT
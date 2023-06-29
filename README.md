# php-duration
Missing PHP TimeUnit and Duration library

## Installation

```bash
composer require monadial/php-duration
```

## Finite Duration
The FiniteDuration class is a representation of a finite duration of time. It provides functionality to perform operations and conversions on durations, such as addition, subtraction, multiplication, division, and conversion to different units of time.

### Factory Methods

- `fromTimeUnit(int $length, TimeUnit $unit): FiniteDuration`: Creates a new instance of the `FiniteDuration` class from the specified length and time unit.
    ```php
    \Monadial\Duration\FiniteDuration::fromTimeUnit(1, TimeUnit::SECONDS); // create 1 second duration
    ```

- `fromNanos(int $nanos): FiniteDuration`: Creates a new instance of the `FiniteDuration` class from the specified duration in nanoseconds.
  ```php
  \Monadial\Duration\FiniteDuration::fromNanos(1000); // create 1000 nanoseconds duration
    ```
  
- `fromString(string $duration): FiniteDuration`: Creates a new instance of the `FiniteDuration` class from a string representation of the duration.
  ```php
  \Monadial\Duration\FiniteDuration::fromTimeUnit('1 minutes'); // create 1 minute duration
    ```
  
### Duration Conversion Methods

- `toNanos(): int`: Converts the duration to nanoseconds.
- `toMicros(): int`: Converts the duration to microseconds.
- `toMillis(): int`: Converts the duration to milliseconds.
- `toSeconds(): int`: Converts the duration to seconds.
- `toMinutes(): int`: Converts the duration to minutes.
- `toHours(): int`: Converts the duration to hours.
- `toDays(): int`: Converts the duration to days.
- `toUnit(TimeUnit $unit): float`: Converts the duration to the specified time unit.

### Arithmetic Operations

- `add(Duration $other): FiniteDuration`: Adds another duration to the current duration.
- `subtract(Duration $other): FiniteDuration`: Subtracts another duration from the current duration.
- `multiply(int $factor): FiniteDuration`: Multiplies the duration by a factor.
- `division(int $divisor): FiniteDuration`: Divides the duration by a divisor.

### Other Methods

- `isFinite(): bool`: Checks if the duration is finite.
- `equals(Duration $other): bool`: Checks if the current duration is equal to another duration.
- `toCoarsest(): Duration`: Converts the duration to the coarsest possible unit.
- `asDateTime(): DateTimeImmutable`: Converts the duration to a `DateTimeImmutable` object.

### Exceptions

The `FiniteDuration` class may throw the following exceptions:

- `FiniteDurationBoundary`: Thrown when the duration exceeds the maximum or minimum value allowed by the time unit.
- `NanosecondsAreNotConvertibleToDateTime`: Thrown when attempting to convert a duration in nanoseconds to a `DateTimeImmutable` object (nanoseconds cannot be represented as a valid date and time).
- `UnableToConvertToDateTime`: Thrown when the duration cannot be converted to a `DateTimeImmutable` object due to limitations or errors.

Please note that this is just an overview of the `FiniteDuration` class and its available methods. For detailed information on each method and its usage, refer to the class implementation and documentation.

Create two tables; day shift and night shift.

When a user is registered, they're automatically assigned a shift. If the last user to be assigned a shift was assigned a day shift, then the next user will be assigned a night shift, and they'll keep alternating like that so as to have balance between those working the day shift and those working the night shift.


A column (shift_type) in the schedule table is to be created that will contain the type of schedule assigned to a particular employee.

There will be two functions, day_shift() and night_shift(), and another function shift_type() that will retrieve the type of shift for each employee and call the appropriate function.

The functions day_shift() and night_shift() will then display the appropriate shift for each employee.

On an employees profile, the "view schedule" link should call the appropriate functions which will display the schedule.
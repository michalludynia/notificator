### ADR regarding implementation of circuit breaker

**Context:**
I have to provide fallback mechanism for notificator component - it is business requirement.
To make the fail possibly fast and improve performance of notificator I would like to introduce circuit breaker pattern.
The circuit breaker tracks availability of each transport and opens or closes the circuit depending of it's availability.
More about circuit breaker pattern you can find here: https://martinfowler.com/bliki/CircuitBreaker.html

**Status:** Accepted

**Decision:** Use Ganesha package (https://github.com/ackintosh/ganesha). 

**Arguments:**
- Good documentation
- Actively developed on GitHub
- Quite Easy to use
- Multiply storage adapters
- The team (me) is already familiar with this tool

**Decision made:** 04-12-2022
 @michalludynia

**Consequences:**
- the storage for ganesha is needed (redis at the moment)

**Alternative packages**: 
- https://github.com/leocarmo/circuit-breaker-php
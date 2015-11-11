<div id="problem-statement">
<h5>Problem</h5>
<p>Given a range of numbers, find all the primes in this range
</p>
<h5>Input</h5>
<p>
    The input contains a single line containing two numbers <b>S</b> and <b>E</b> space separated followed with or without a new line
  </p>
<h6>Example</h6>
<h5>Input</h5>
<div class="sample-data">
<code>
<pre>
10 14

</pre>
</code>
</div>
<h5>Output</h5>
<p>Your program should generate all the numbers in the range inclusive of the start and end numbers and then output for each number in the range on one line &quot;<b>#Y: X </b> &quot; (NB: quotes for clarity), where Y is the case number starting from one and X is either <b>prime</b> or <b>not prime</b> on whether the number is a prime followed by a new line.<br />
<b>NOTE:</b><i> There should be exactly one space between the colon and X</i>
</p>
<h6>Example</h6>
<div class="sample-data">
<code>
<pre>
#1: not prime
#2: prime
#3: not prime
#4: prime
#5: not prime
</pre>
</code>
</div>
<p>Here we are testing for the range 10 to 14 so starting from 10 we have 10 which is not a prime, 11 which is a prime, 12 which is not a prime, 13 which is a prime and lastly 14 which is not a prime</p>
</div>
<h5>Limits</h5>
<p>
1&lt;<b>S,E</b>&lt;2<sup>30</sup>
</p>

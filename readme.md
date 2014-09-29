# Coding Challenge Submission

The goal of this code is to find a subset of products in Zappos's catalog that most closely matches a target value. This example uses the Zappos API to get products, and is limited to the first 500 items returned by the API.

Rather than calculating the sum of every possible combination of products (as this would get unmanageable and not scale with Zappos's massive product catalog), this solution loops through the array several times looking for specific criteria. The goal is to find items that are close to the average price per item, and then add these together to form a subset that equal the target amount.

For example, let's say we want four items that add up to $300. We first try to find items that cost $75. If this doesn't work, we try to find items that are $73-77 dollars and put them in a combination. We keep increasing this variance until we are comfortable we have found enough suitable combinations, then check each one to find out which is the closest to the target amount.

The number of repetitions attempted by the code can be controlled using a constant. Having more repetitions increases the likelihood that an optimal combination will be found, but requires more API calls and computation time. If the goal is to have the most efficient solution, this number can be decreased.

### To Run: ###

Using a web server running PHP, load index.php. This will prompt the user with a form asking for the parameters in question. After submitting, the page will display a PHP array of the products requested. Since this would likely be a component in a larger program, it makes more sense to return an array than to display an interface.

### Contact: ###
Andrew Stephens

andrew@andrewmediaprod.com

(860) 578 - 7521
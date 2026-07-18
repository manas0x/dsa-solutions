class Solution {
    int gcd(int a , int b){
        if (a == 0 || b == 0) return 0;

        int minNum = Math.min(a,b);
        while (minNum > 0){
            if ( a % minNum == 0 && b % minNum == 0){
                return minNum;
            }
            minNum--;
        }

        return minNum;
    }

    public int findGCD(int[] nums) {
        int n = nums.length;
        int min = nums[0];
        int max = nums[0];

        for(int i = 1 ; i < n ; i++){
            min = Math.min(min , nums[i]);
            max = Math.max(max , nums[i]);
        }

        return gcd(min , max);
    }
}
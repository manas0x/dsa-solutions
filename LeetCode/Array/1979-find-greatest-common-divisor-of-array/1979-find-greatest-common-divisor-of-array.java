class Solution {
    public int findGCD(int[] nums) {
        int min = Integer.MAX_VALUE;
        int max = Integer.MIN_VALUE;
        
        for(int num : nums){
            min = Math.min(min , num);
            max = Math.max(max , num);
        }

        while (max != 0){
            int t = min % max;
            min = max;
            max = t;
        }
        return min;
    }
}
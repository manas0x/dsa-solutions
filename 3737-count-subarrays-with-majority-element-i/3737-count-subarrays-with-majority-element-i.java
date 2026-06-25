class Solution {
    public int countMajoritySubarrays(int[] nums, int target) {
        int count = 0;
        int len = nums.length;
        
        for (int i = 0 ; i < len ; i++){
            int freq = 0;
            for(int j = i ; j < len ; j++){
                if (nums[j] == target){
                    freq++;
                }
                int SubArrayLen = (j-i)  + 1;

                if (freq > SubArrayLen/2){
                    count++;
                }
            }
        }
        return count;
    }
}
class Solution {
    public boolean lemonadeChange(int[] bills) {
        int five = 0;
        int ten = 0;

        for(int i = 0 ; i < bills.length ; i++){
            int bill = bills[i];

            if(bill == 5){
                five++;
            }

            else if (10 == bill){
                if(five > 0){
                    ten++;
                    five--;
                }
                else{
                    return false;
                }
            }
            else{
                if (ten > 0 && five > 0){
                    ten--;
                    five--;
                } 
                else if (five >= 3){
                    five-=3;
                }
                else{
                    return false;
                }
            }
        }
        return true;
    }
}
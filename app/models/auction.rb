class Auction < ActiveRecord::Base
  attr_accessible :user_id, :end_time, :id, :price, :listing_id
  belongs_to :listing
  belongs_to :user
  
  validates :price, :listing_id, :presence => true
end

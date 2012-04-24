class Listing < ActiveRecord::Base
  attr_accessible :id, :name, :retail, :image_url
  has_many :auctions
end

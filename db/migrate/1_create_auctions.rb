class CreateAuctions < ActiveRecord::Migration
  def change
    create_table :auctions do |t|
      t.integer :listing_id
      t.decimal :price
      t.integer :bidder_id
      t.integer :end_time
      
      t.timestamps
    end
    add_index :auctions, :id, :unique => true
  end
end
